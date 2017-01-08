<?php namespace App\Http\Controllers\Auth;

use App\Events\PostLogin;
use App\Http\Controllers\Controller;
use Event;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Input;
use Marwelln\Recaptcha\Model;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class AuthController extends Controller {

	use AuthenticatesAndRegistersUsers;

	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);

		parent::__construct();
	}

	public function postLogin(Request $request)
	{
		$rules = [
			'email' => 'required|email',
			'password' => 'required',
		];

		// reCaptcha validation
		if(config('global.recaptcha-sitekey') && config('global.recaptcha-secretkey') && env('APP_ENV') == 'production') {
			$validator = new Model(Input::get('g-recaptcha-response'), config('global.recaptcha-secretkey'));
			$validated = $validator->validate();

			if(!$validated) {
				return redirect($this->loginPath())
					->withInput($request->only('email', 'remember'))
					->withErrors([
						'g-recaptcha-response' => trans('app.recaptcha_failed'),
					]);
			}
		}

		$this->validate($request, $rules);

		$credentials = $request->only('email', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			Event::fire(new PostLogin());

			return redirect()->intended('/');
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => $this->getFailedLoginMessage(),
			]);
	}

	public function getRegister(Request $request)
	{
		// no registrations
		return redirect()->intended('/');
	}

	public function postRegister(Request $request)
	{
		// no registrations
		return redirect()->intended('/');
	}

}
