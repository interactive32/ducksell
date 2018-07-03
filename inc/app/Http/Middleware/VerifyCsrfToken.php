<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Symfony\Component\Security\Core\Util\StringUtils;

/**
 * Middleware
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class VerifyCsrfToken extends BaseVerifier {

	public function handle($request, Closure $next)
	{
		return parent::handle($request, $next);
	}

	/**
	 * Determine if the session and input CSRF tokens match.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function tokensMatch($request)
	{
		$token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

		if ( ! $token && $header = $request->header('X-XSRF-TOKEN'))
		{
			$token = $this->encrypter->decrypt($header);
		}

		// symfony StringUtils bug when token is not a string (Array to string conversion Exception)
		if(!is_string($token)) {
			return false;
		}

		return $request->session()->token() == $token;
	}

}
