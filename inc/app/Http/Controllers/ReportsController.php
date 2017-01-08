<?php namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Download;
use App\Models\Log;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Input;
use Redirect;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ReportsController extends Controller {


	public function __construct()
	{
		parent::__construct();
	}

	public function logs()
	{

		$builder = Log::search(['type', 'message', 'data'])->orderBy('id', 'desc');

		if ($this->isExport()) {
			return $this->export(
				$builder->selectRaw('id, created_at time, type, message, data')
			);
		}

		$log = $builder->paginate(session('per_page'));
		$log->setPath('logs');

		return view('reports.logs')->with([
			'page_title' => trans('app.logs'),
			'data' => $log,
		]);
	}

	public function purgeLogs()
	{
		$Log = new Log();
		$Log->truncate();

		return Redirect::to('logs');
	}

	public function analytics()
	{
		$analytics = new Analytic();
		$analytics->setSiteId(Input::get('site_id', 0));

		return view('reports.analytics')->with([
			'page_title' => trans('app.reports'),
			'data_today' => $analytics->getVisitsToday(),
			'data_this_week' => $analytics->getVisitsByDays(Carbon::create()->startOfWeek(), 7),
			'data_last_week' => $analytics->getVisitsByDays(Carbon::create()->subWeek()->startOfWeek(), 7),
			'data_this_month' => $analytics->getVisitsByDays(Carbon::create()->startOfMonth(), Carbon::create()->startOfMonth()->daysInMonth),
			'data_last_month' => $analytics->getVisitsByDays(Carbon::create()->subMonth()->startOfMonth(), Carbon::create()->subMonth()->startOfMonth()->daysInMonth),
			'data_this_year' => $analytics->getVisitsByMonths(Carbon::create()->startOfYear()),
			'sales_today' => Transaction::getSalesByProduct(Carbon::create()->startOfDay()),
			'sales_this_week' => Transaction::getSalesByProduct(Carbon::create()->startOfWeek()),
			'sales_last_week' => Transaction::getSalesByProduct(Carbon::create()->subWeek()->startOfWeek(), Carbon::create()->subWeek()->endOfWeek()),
			'sales_this_month' => Transaction::getSalesByProduct(Carbon::create()->startOfMonth()),
			'sales_last_month' => Transaction::getSalesByProduct(Carbon::create()->subMonth()->startOfMonth(), Carbon::create()->subMonth()->endOfMonth()),
			'sales_this_year' => Transaction::getSalesByProduct(Carbon::create()->startOfYear()),
			'sales_last_year' => Transaction::getSalesByProduct(Carbon::create()->subYear()->startOfYear(), Carbon::create()->subYear()->endOfYear()),
			'sales_all_time' => Transaction::getSalesByProduct(Carbon::create()->startOfCentury()),
		]);
	}

	public function conversions()
	{
		$builder = User::customers()
			->withTrashed()
			->join('transactions', 'transactions.user_id', '=', 'users.id')
			->leftJoin('user_metadata as referral', function($join)
			{
				$join->on('referral.user_id', '=', 'users.id')
					->where('referral.key', '=' ,'referral');
			})
			->leftJoin('user_metadata as landing_page', function($join)
			{
				$join->on('landing_page.user_id', '=', 'users.id')
					->where('landing_page.key', '=' ,'landing_page');
			})
			->orderBy('transactions.id', 'DESC')
			->search(['referral.value', 'landing_page.value']);

		if ($this->isExport()) {

			return $this->export(
				$builder->selectRaw("
				transactions.created_at transaction_date,
				referral.value referral,
				landing_page.value landing_page,
				users.name customer,
				users.email,
				users.created_at customer_registration,
				transactions.id transaction_id,
				CASE
					WHEN transactions.status_id = 1 THEN '".trans('app.transaction_status_1')."'
					WHEN transactions.status_id = 2 THEN '".trans('app.transaction_status_2')."'
					WHEN transactions.status_id = 3 THEN '".trans('app.transaction_status_3')."'
					WHEN transactions.status_id = 4 THEN '".trans('app.transaction_status_4')."'
					WHEN transactions.status_id = 5 THEN '".trans('app.transaction_status_5')."'
					WHEN transactions.status_id = 6 THEN '".trans('app.transaction_status_6')."'
					ELSE transactions.status_id
				  END as status,
				transactions.listed_currency,
				transactions.listed_amount/100 listed_amount
				")
			);
		}

		$builder->selectRaw('
		transactions.created_at,
		transactions.id transaction_id,
		transactions.status_id,
		transactions.listed_currency,
		transactions.listed_amount,
		referral.value referral,
		landing_page.value landing_page,
		users.name customer,
		users.id user_id,
		users.created_at customer_registration
		');

		$conversions = $builder->paginate(session('per_page'));
		$conversions->setPath('conversions');

		return view('reports.conversions')->with([
			'page_title' => trans('app.conversions'),
			'data' => $conversions,
		]);
	}

}
