<?php namespace App\Models;

use App\Events\EmailPurchase;
use App\Events\TransactionCreated;
use App\Services\AmountService;
use App\Services\TemplateService;
use App\Services\Util;
use Auth;
use Carbon\Carbon;
use Event;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Transaction extends BaseModel
{
	public $guarded = [];

	const STATUS_APPROVED = 1;
	const STATUS_PENDING = 2;
	const STATUS_REFUNDED = 3;
	const STATUS_CANCELED = 4;
	const STATUS_FRAUD = 5;
	const STATUS_EXPIRED = 6;

	public function metadata()
	{
		return $this
			->hasMany('App\Models\TransactionMetadata');
	}

	public function updates()
	{
		return $this
			->hasMany('App\Models\TransactionUpdate');
	}

	public function products()
	{
		return $this
			->BelongsToMany('App\Models\Product')
			->withPivot('id', 'license_number', 'processor_amount', 'listed_amount', 'customer_amount')
			->with('files')
			->withTrashed();
	}

	public function customer()
	{
		return $this
			->belongsTo('App\Models\User', 'user_id', 'id')
			->withTrashed();
	}

	public function createTransaction($payment_processor, $customer_id, AmountService $amount, $status_id, $external_sale_id = 0)
	{
		$hash = Util::generateTransactionHash($external_sale_id);

		try {
			$ret = $this->create([
				'status_id' => $status_id,
				'user_id' => $customer_id,
				'payment_processor' => $payment_processor,
				'external_sale_id' => $external_sale_id,
				'processor_currency' => $amount->getProcessorCurrency(),
				'processor_amount' => $amount->getProcessorAmount(),
				'listed_currency' => $amount->getListedCurrency(),
				'listed_amount' => $amount->getListedAmount(),
				'customer_currency' => $amount->getCustomerCurrency(),
				'customer_amount' => $amount->getCustomerAmount(),
				'hash' => $hash,
			]);

		} catch (\Exception $e) {
			Log::writeException($e);
			return false;
		}

		if ($ret) {
			Log::write('log_new_transaction_created', $ret->toJson());
		}

		Event::fire(new TransactionCreated($ret));

		return $ret;
	}

	public function sendPurchaseInformationEmail($hash)
	{
		$eventEmail = Event::fire(new EmailPurchase($hash));
		
		if(!empty($eventEmail)) {
			return true;
		}

		$customer = $this->getCustomerByTransactionHash($hash);

		if(!$customer) {
			return false;
		}

		$direct_link = url('/download') .'?q='. $hash;

		$password = trans('app.use_your_old_password');

		// do not update password if already set
		if(!$customer->password) {
			$password = Util::generatePassword();
			$customer->password = bcrypt($password);
			$customer->save();
		}

		$TemplateService = new TemplateService();
		$TemplateService->loadSystemTemplate('thankyou');
		$TemplateService->setVars([
			'direct_link' => $direct_link,
			'user_email' => $customer->email,
			'user_password' => $password,
		]);

		$ret = Util::sendMail($customer->email, trans('app.purchase_information'), $TemplateService->render());

		if(!$ret) {
			Log::write('log_cannot_send_email', $customer->email, true, Log::TYPE_CRITICAL);
			return false;
		}

		return true;
	}

	public function getTransactionByExternalSaleId($external_sale_id)
	{
		return $this::where('external_sale_id', $external_sale_id)->first();
	}

	public function setStatus($transaction_id, $status_id)
	{
		$transaction = $this::findOrFail($transaction_id);
		$transaction->status_id = $status_id;
		return $transaction->save();
	}

	public function getDownloads()
	{
		if(!isCustomer()) {
			return false;
		}

		$ret = $this
			->with('products')
			->where('user_id', Auth::user()->id)
			->orderBy('created_at', 'desc')
			->get();

		return $ret;
	}

	public static function getCustomerByTransactionHash($hash)
	{
		$ret = self::where('hash', $hash)->first();

		if(!$ret) {
			return null;
		}

		return $ret->customer;
	}

	public static function getSalesAmountByCurrency(Carbon $time_from, Carbon $time_to = null, $product_id = null)
	{
		$builder = self::
			whereIn('transactions.status_id', [self::STATUS_APPROVED, self::STATUS_PENDING])
			->join('product_transaction', 'product_transaction.transaction_id', '=', 'transactions.id')
			->where('transactions.created_at', '>=', $time_from)
			->groupBy('transactions.listed_currency')
			->selectRaw('transactions.listed_currency, sum(product_transaction.listed_amount) as listed_amount');

		if($time_to) {
			$builder->where('transactions.created_at', '<=', $time_to);
		}

		if($product_id) {
			$builder->where('product_transaction.product_id', $product_id);
		}

		return $builder->get();
	}

	public static function getSalesByProduct(Carbon $time_from, Carbon $time_to = null)
	{
		$builder = self::
		whereIn('transactions.status_id', [self::STATUS_APPROVED, self::STATUS_PENDING])
			->join('product_transaction', 'product_transaction.transaction_id', '=', 'transactions.id')
			->join('products', 'product_transaction.product_id', '=', 'products.id')
			->where('transactions.created_at', '>=', $time_from)
			->groupBy('transactions.listed_currency')
			->groupBy('products.id')
			->orderBy('products.name')
			->orderBy('transactions.listed_currency')
			->selectRaw('
				products.id product_id,
				products.name product_name,
				transactions.listed_currency listed_currency,
				sum(product_transaction.listed_amount) listed_amount,
				count(product_transaction.id) transaction_count
			');

		if($time_to) {
			$builder->where('transactions.created_at', '<=', $time_to);
		}

		return $builder->get();
	}

	public static function getSalesCount(Carbon $time_from,  Carbon $time_to = null, $product_id = null)
	{

		$builder = self::
			whereIn('status_id', [self::STATUS_APPROVED, self::STATUS_PENDING])
			->join('product_transaction', 'product_transaction.transaction_id', '=', 'transactions.id')
			->where('created_at', '>=', $time_from);

		if($time_to) {
			$builder->where('created_at', '<=', $time_to);
		}

		if($product_id) {
			$builder->where('product_transaction.product_id', $product_id);
		}

		return $builder->count('*');
	}

	public static function hasExpired(Transaction $transaction)
	{
		if(!config('global.license-expiration')) {
			return false;
		}

		$today = Carbon::create();

		return $transaction->created_at < $today->subDays(config('global.license-expiration'));
	}
}
