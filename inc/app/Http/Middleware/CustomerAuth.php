<?php namespace App\Http\Middleware;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Util;
use Closure;
use Illuminate\Auth\Guard;
use Input;
use Route;

/**
 * Middleware
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class CustomerAuth {

	protected $auth;

	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	public function handle($request, Closure $next)
	{
		$hash = Input::get('q', null);

		if($hash)
		{
			// direct links allowed
			if(config('global.allow-direct-links')) {

				$customer = Transaction::getCustomerByTransactionHash($hash);

				if(!$customer) {
					return redirect()->to('auth/login');
				}

				if($customer->trashed()){
					return redirect()->to('auth/login');
				}

				// login customer
				$this->auth->login($customer);

				// connect customer with session referral
				User::updateReferral();

				// redirect to clear the hash from url
				return redirect()->to(Route::getCurrentRoute()->getPath());

			} else {
				return redirect()->to('auth/login');
			}

		}
		elseif($this->auth->check())
		{
			if ($this->auth->user()->role != User::CUSTOMER_ROLE && Util::getRouteIdentifier() != 'download@printinvoice') {

				// redirect non-customers away from here
				flash()->error(trans('app.customer_area_only'));
				return redirect()->to('');
			}
		}
		else
		{
			return redirect()->to('auth/login');
		}

		return $next($request);
	}

}
