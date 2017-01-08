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
class AdminAuth {

	protected $auth;

	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	public function handle($request, Closure $next)
	{
		if(!$this->auth->check() || $this->auth->user()->role != User::ADMIN_ROLE)
		{
			flash()->error(trans('app.not_authorized'));
			return redirect()->to('');
		}

		return $next($request);
	}

}
