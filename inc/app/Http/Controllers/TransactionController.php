<?php namespace App\Http\Controllers;

use App\Events\ContentTransactionsEdit;
use App\Models\Log;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionUpdate;
use App\Models\User;
use App\Services\AmountService;
use App\Services\ManualTransaction;
use App\Services\Util;
use Event;
use Input;
use Redirect;
use Validator;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class TransactionController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$builder = Transaction::
			join('users', 'users.id', '=', 'transactions.user_id')
			->leftJoin('product_transaction', 'product_transaction.transaction_id', '=', 'transactions.id')
			->leftJoin('products', 'products.id', '=', 'product_transaction.product_id')
			->search(['transactions.id', 'payment_processor', 'external_sale_id', 'users.name', 'users.email', 'product_transaction.license_number', 'products.name'])
			->orderBy('transactions.id', 'desc');

		if ($this->isExport()) {
			return $this->export(
				$builder
					->selectRaw("
				transactions.id,
				transactions.external_sale_id,
				transactions.created_at date,
				  CASE
					WHEN transactions.status_id = 1 THEN '".trans('app.transaction_status_1')."'
					WHEN transactions.status_id = 2 THEN '".trans('app.transaction_status_2')."'
					WHEN transactions.status_id = 3 THEN '".trans('app.transaction_status_3')."'
					WHEN transactions.status_id = 4 THEN '".trans('app.transaction_status_4')."'
					WHEN transactions.status_id = 5 THEN '".trans('app.transaction_status_5')."'
					WHEN transactions.status_id = 6 THEN '".trans('app.transaction_status_6')."'
					ELSE transactions.status_id
				  END as status,
				transactions.status_id status_id,
				transactions.payment_processor,
				users.name customer_name,
				users.email customer_email,
				transactions.hash transaction_hash,
				products.name product,
				product_transaction.license_number,
				transactions.processor_currency,
				product_transaction.processor_amount/100 as processor_amount,
				transactions.listed_currency,
				product_transaction.listed_amount/100 as listed_amount,
				transactions.customer_currency,
				product_transaction.customer_amount/100 as customer_amount
				")
			);
		}

		$builder->with('customer');
		$builder->selectRaw("
				transactions.id,
				transactions.external_sale_id,
				transactions.created_at,
				transactions.status_id,
				transactions.payment_processor,
				users.name customer_name,
				users.email customer_email,
				transactions.hash,
				transactions.listed_currency,
				product_transaction.listed_amount,
				products.name product_name,
				product_transaction.license_number
				");

		$transactions = $builder->paginate(session('per_page'));
		$transactions->setPath('transactions');

		return view('transactions.index')->with([
			'page_title' => trans('app.transactions'),
			'data' => $transactions,
		]);
	}

	public function create($customer_id)
	{
		return view('transactions.create')->with([
			'page_title' => trans('app.transaction') . ' | ' . trans('app.add_new'),
			'products' => Product::lists('name', 'id'),
			'customer' => User::findOrFail($customer_id),
		]);
	}

	public function store($customer_id)
	{
		$rules = [
			'product_id'	=> 'required',
			'listed_amount' 	=> 'numeric|required',
			'listed_currency' 	=> 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('transactions/create/'.$customer_id)
				->withErrors($validator)
				->withInput();
		}
		elseif(!Product::find(Input::get('product_id'))) {
			return Redirect::to('transactions/create/'.$customer_id)
				->withErrors(trans('app.record_with_id_not_found'))
				->withInput();
		}
		else {

			$amount = new AmountService();
			$amount->setListedAmount(Input::get('listed_amount'));
			$amount->setListedCurrency(Input::get('listed_currency'));

			$amount->setProcessorAmount(Input::get('listed_amount'));
			$amount->setProcessorCurrency(Input::get('listed_currency'));

			$amount->setCustomerAmount(Input::get('customer_amount') > 0 ? Input::get('customer_amount') : Input::get('listed_amount'));
			$amount->setCustomerCurrency(Input::get('customer_currency', false) ? Input::get('customer_currency') : Input::get('listed_currency'));

			$ManualTransaction = new ManualTransaction($customer_id, Input::get('product_id'), $amount, Input::get('send_email_to_customer', 0));

			try {
				$ManualTransaction->createOrder();
				flash()->success(trans('app.success'));
				return Redirect::to('customers/'.$customer_id.'/edit');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('transactions/create/'.$customer_id)
					->withErrors($e->getMessage())
					->withInput();
			}

		}
	}

	public function edit($id)
	{
		$transaction = Transaction::with('products')->findOrFail($id);

		$plugins_transaction_edit = '';
		$transaction_edit = Event::fire(new ContentTransactionsEdit($id));

		if($transaction_edit) {
			foreach($transaction_edit as $transaction_edit_content) {
				$plugins_transaction_edit .= $transaction_edit_content;
			}
		}

		return view('transactions.edit')->with([
			'page_title' => trans('app.transaction') . ' | ' . trans('app.edit'),
			'transaction' => $transaction,
			'plugins_transactions_edit' => $plugins_transaction_edit,
		]);
	}

	public function update($id)
	{
		$TransactionUpdate = new TransactionUpdate();

		$rules = [
			'transaction_status' => 'required|numeric|between:1,6',
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('transactions/'.$id.'/edit')
				->withErrors($validator)
				->withInput();
		}
		else {
			$transaction = Transaction::findOrFail($id);
			$transaction->status_id = Input::get('transaction_status');

			try {
				$transaction->save();
				$TransactionUpdate->updateTransaction($transaction->id, 'trx_update_manual', Util::getCurrentUserInfo(), 'transaction_status_'.$transaction->status_id);
				flash()->success(trans('app.success'));
				return Redirect::to('transactions/'.$id.'/edit');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('transactions/'.$id.'/edit')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

}
