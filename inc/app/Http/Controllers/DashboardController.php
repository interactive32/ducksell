<?php namespace App\Http\Controllers;

use App\Models\SessionTable;
use App\Models\Transaction;
use App\Services\AmountService;
use Carbon\Carbon;
use Request;
use View;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class DashboardController extends Controller {


	public function __construct()
	{
		parent::__construct();
	}


	public function dashboard()
	{
		return view('dashboard.dashboard')->with([
			'page_title' => trans('app.dashboard'),
		]);
	}

	public function getdata()
	{
		$time_ago_min = (int) Request::input('t', 3);
		$site_select = (int) Request::input('s', 0);

		$SessionTable = new SessionTable();
		$users = $SessionTable->getActiveUsersData($time_ago_min);
		$today_sales_count = Transaction::getSalesCount(Carbon::create()->startOfDay());
		$today_sales_amount_by_currency = Transaction::getSalesAmountByCurrency(Carbon::create()->startOfDay());

		$regions = [];
		$locations = [];
		$city_count = [];
		$pages = [];
		$referrals = [];
		$countries = [];
		$total_online = 0;
		$browser_desktop = 0;
		$browser_mobile = 0;
		$bounced = 0;
		$new_users = 0;

		foreach ($users as $user) {

			$session_data = unserialize(base64_decode($user->payload));

			// invalid session
			if (!isset($session_data['first_visit']) || !isset($session_data['last_visit'])) {
				continue;
			}

			$first_visit = $session_data['first_visit'];
			$last_visit = $session_data['last_visit'];

			// filter by site
			if ($site_select != 0 && $last_visit->site_id != $site_select) {
				continue;
			}

			// after filtering we can count this user in
			$total_online++;

			// allow 3 minutes for first interaction and set bounced if no additional requests
			if($first_visit->created_at_timestamp < time() - 3*60 && $first_visit->created_at_timestamp == $last_visit->created_at_timestamp) {
				++$bounced;
			}

			if($first_visit->created_at_timestamp > time() - $time_ago_min * 60) {
				++$new_users;
			}

			if ($first_visit->referral) {

				if(!isset($referrals[$first_visit->referral])) {
					$referrals[$first_visit->referral] = 0;
				}
				++$referrals[$first_visit->referral];
			}

			if ($last_visit->geo_country_code) {
				if (!isset($regions[$last_visit->geo_country_code])){
					$regions[$last_visit->geo_country_code] = 1;
				} else {
					++$regions[$last_visit->geo_country_code];
				}

				if ($last_visit->geo_country_name) {
					if(!isset($countries[$last_visit->geo_country_name])) {
						$countries[$last_visit->geo_country_name] = 0;
					}
					++$countries[$last_visit->geo_country_name];
				}

				if ($last_visit->geo_latitude && $last_visit->geo_longitude) {

					if (!$last_visit->geo_city) {
						$last_visit->geo_city = $last_visit->geo_country_code.': unknown city';
					}

					if (isset($city_count[$last_visit->geo_city])){
						++$city_count[$last_visit->geo_city];
					} else {
						$city_count[$last_visit->geo_city] = 1;
					}

					$locations[] = [
						'latLng' => [$last_visit->geo_latitude, $last_visit->geo_longitude],
						'name' => $last_visit->geo_city . ' ('.$city_count[$last_visit->geo_city].')',
						'total' => $city_count[$last_visit->geo_city]];
				}
			}


			if ($last_visit->landing_page) {
				if(!isset($pages[$last_visit->landing_page])) {
					$pages[$last_visit->landing_page] = 0;
				}
				++$pages[$last_visit->landing_page];
			}

			if ($last_visit->browser_type == 'desktop') {
				++$browser_desktop;
			}

			if ($last_visit->browser_type == 'mobile') {
				++$browser_mobile;
			}

		}

		arsort($pages);
		$pages = array_slice($pages, 0, 10);
		$top_pages_view = View::make('partials.top_pages', ['data' => $pages])->render();

		arsort($referrals);
		$referrals = array_slice($referrals, 0, 10);
		$top_referrals_view = View::make('partials.top_referrals', ['data' => $referrals])->render();

		arsort($countries);
		$countries = array_slice($countries, 0, 10);
		$top_countries_view = View::make('partials.top_countries', ['data' => $countries])->render();

		$browser_desktop_percent = round($browser_desktop / ($browser_desktop + $browser_mobile + 0.0001)  * 100);
		$browser_mobile_percent = $browser_desktop_percent == 0 ? 0 : 100 - $browser_desktop_percent;

		$bounce_rate = round($bounced / ($total_online + 0.0001) * 100);
		$new_users_rate = round($new_users / ($total_online + 0.0001) * 100);

		$today_sales_amount = '';
		foreach($today_sales_amount_by_currency as $key => $sales_amount) {
			if($key > 0) {
				$today_sales_amount .= ' / ';
			}
			$today_sales_amount .= AmountService::displayAmount($sales_amount->listed_amount, $sales_amount->listed_currency);
		}
		if(!$today_sales_amount) {
			$today_sales_amount = AmountService::displayAmount(0);
		}

		$data = [
			'regions' => $regions,
			'locations' => $locations,
			'top_pages_view' => $top_pages_view,
			'top_referrals_view' => $top_referrals_view,
			'top_countries_view' => $top_countries_view,
			'total_online' => $total_online,
			'browser_desktop' => $browser_desktop_percent,
			'browser_mobile' => $browser_mobile_percent,
			'bounced' => $bounced,
			'bounce_rate' => $bounce_rate,
			'new_users' => $new_users,
			'new_users_rate' => $new_users_rate,
			'today_sales_count' => $today_sales_count,
			'today_sales_amount' => $today_sales_amount,
		];

		return $data;
	}

}
