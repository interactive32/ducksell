<?php namespace App\Http\Controllers;


use App\Models\Log;
use App\Models\User;
use Auth;
use Exception;
use Input;
use Redirect;
use URL;
use Validator;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ProfileController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function edit()
	{
		return view('profile.edit')->with([
			'page_title' => trans('app.profile'),
			'profile' => User::findOrFail(Auth::user()->id)
		]);
	}

	public function update()
	{
		$rules = [
			'name'		=> 'required',
			'email'		=> 'required|email',
			'password'		=> 'confirmed|min:'.config('global.min-password'),
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('profile')
				->withErrors($validator)
				->withInput();
		}
		else {
			$user = User::findOrFail(Auth::user()->id);
			$user->name = Input::get('name');
			$user->email = Input::get('email');

			if(Input::get('password')) {
				$user->password = bcrypt(Input::get('password'));
			}

			try {
				$user->save();
				flash()->success(trans('app.success'));
				return Redirect::to('profile');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('profile')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

	public function setPerPage($num)
	{
		if(in_array($num, config('global.per_page'))) {
			session(['per_page' => $num]);
		}

		return Redirect::to(explode('?', URL::previous())[0]);
	}

}
