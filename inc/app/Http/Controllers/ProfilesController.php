<?php namespace App\Http\Controllers;


use App\Models\Log;
use App\Models\User;
use Auth;
use Exception;
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
class ProfilesController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$profiles = User::search(['email', 'name'])->profiles()->paginate(session('per_page'));
		$profiles->setPath('profiles');

		return view('profiles.index')->with([
			'page_title' => trans('app.profiles'),
			'data' => $profiles,
		]);
	}

	public function create()
	{
		return view('profiles.create')->with([
			'page_title' => trans('app.profile') . ' | ' . trans('app.add_new'),
		]);
	}

	public function store()
	{
		$rules = [
			'name'				=> 'required',
			'email'				=> 'required|email|unique:users',
			'role'				=> 'required|numeric',
			'password'		=> 'required|confirmed|min:'.config('global.min-password'),
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('profiles/create')
				->withErrors($validator)
				->withInput();
		}
		else {
			$user = new User();
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->role = Input::get('role');
			$user->password = bcrypt(Input::get('password'));

			try {
				$user->save();
				flash()->success(trans('app.success'));
				return Redirect::to('profiles');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('profiles/create')
					->withErrors($e->getMessage())
					->withInput();
			}

		}
	}

	public function edit($id)
	{
		if($id == Auth::user()->id) {
			return Redirect::to('profile');
		}

		return view('profiles.edit')->with([
			'page_title' => trans('app.profile') . ' | ' . trans('app.edit'),
			'user' => User::withTrashed()->findOrFail($id)
		]);
	}

	public function update($id)
	{
		$rules = [
			'name'		=> 'required',
			'email'		=> 'required|email',
			'role'		=> 'required',
			'password'		=> 'confirmed|min:'.config('global.min-password'),
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('profiles/'.$id.'/edit')
				->withErrors($validator)
				->withInput();
		}
		else {
			$user = User::withTrashed()->findOrFail($id);
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->role = Input::get('role');

			if(Input::get('password')) {
				$user->password = bcrypt(Input::get('password'));
			}

			try {
				$user->save();
				if($user->trashed()) {
					$user->restore();
				}
				flash()->success(trans('app.success'));
				return Redirect::to('profiles');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('profiles/create')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

	public function destroy($id)
	{
		try {
			User::softDelete($id, url('profiles/'.$id.'/edit'));
			flash()->success(trans('app.success'));
		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error').' '.$e->getMessage());
		}

		return Redirect::to('profiles');
	}
}
