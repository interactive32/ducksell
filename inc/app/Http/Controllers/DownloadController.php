<?php namespace App\Http\Controllers;

use App\Events\CustomerDownloads;
use App\Events\DownloadFile;
use App\Events\PrintCertificate;
use App\Events\PrintInvoice;
use App\Models\Download;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Auth;
use Event;
use Input;
use Redirect;
use Request;
use Response;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class DownloadController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$Transactions = new Transaction();
		$transactions = $Transactions->getDownloads();

		$custom_view = Event::fire(new CustomerDownloads($transactions));

		if(!empty($custom_view)) {
			return $custom_view[0];
		}

		return view('download')->with([
			'page_title' => trans('app.download'),
			'customer' => Auth::user(),
			'transactions' => $transactions,
		]);
	}

	public function downloadFile($license_id, $file_id)
	{
		$file = ProductTransaction::getFileByLicenseNumber($license_id, $file_id);
		$transaction = ProductTransaction::getTransactionByLicenseNumber($license_id);

		if(!$file || !$transaction || Transaction::hasExpired($transaction)) {
			return Redirect::to('download');
		}

		// allow only approved transactions or pending if configured in options
		if(!($transaction->status_id == Transaction::STATUS_APPROVED || ($transaction->status_id == Transaction::STATUS_PENDING && config('global.allow-download-pending')))) {
			return Redirect::to('download');
		}

		Event::fire(new DownloadFile($license_id, $file));

		// log downloads
		$download = new Download();
		$download->user_id = Auth::user()->id;
		$download->file_name = $file->file_name;
		$download->ip_address = Request::getClientIp();
		$download->save();

		return Response::download(config('global.file_path') . $file->file_name_internal, $file->file_name);
	}

	public function printCertificate($license_number)
	{
		$transaction = ProductTransaction::getTransactionByLicenseNumber($license_number);
		$product = ProductTransaction::getProductByLicenseNumber($license_number);

		if(!$transaction || !$product || !config('global.allow-certificate')) {
			return Redirect::to('download');
		}

		// allow only approved transactions or pending if configured in options
		if(!($transaction->status_id == Transaction::STATUS_APPROVED || ($transaction->status_id == Transaction::STATUS_PENDING && config('global.allow-download-pending')))) {
			return Redirect::to('download');
		}

		$custom_view = Event::fire(new PrintCertificate($transaction, $product));

		if(!empty($custom_view)) {
			return $custom_view[0];
		}

		return view('print.certificate')->with([
			'transaction' => $transaction,
			'product' => $product,
			'license_number' => $license_number,
		]);
	}

	public function printInvoice($hash)
	{
		$currency = Input::get('currency', 'customer');

		$transaction = Transaction::where('hash', $hash)->first();

		if(!$transaction || (!config('global.allow-invoice') && isCustomer())) {
			return Redirect::to('download');
		}

		// allow only approved transactions or pending if configured in options
		if(!($transaction->status_id == Transaction::STATUS_APPROVED || ($transaction->status_id == Transaction::STATUS_PENDING && config('global.allow-download-pending')))) {
			return Redirect::to('download');
		}

		$custom_view = Event::fire(new PrintInvoice($transaction, $currency));

		if(!empty($custom_view)) {
			return $custom_view[0];
		}

		return view('print.invoice')->with([
			'transaction' => $transaction,
			'currency' => $currency,
		]);
	}

}
