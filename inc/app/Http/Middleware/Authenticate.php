<?php namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Guard;

/**
 * Middleware
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Authenticate {

	protected $auth;

	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('auth/login');
			}
		}
		elseif($this->auth->user()->role == 0)
		{
			// connect customer with session referral
			User::updateReferral();

			// redirect customers page
			return redirect()->to('download');
		}

		return $next($request);
	}

}
