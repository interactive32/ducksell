<?php namespace App\Http\Controllers;


use App\Events\ContentCustomersEdit;
use App\Models\Download;
use App\Models\Log;
use App\Models\User;
use Event;
use Input;
use Lang;
use Redirect;
use Validator;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class CustomerController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$builder = User::search(['email', 'name'])->customers();

		if ($this->isExport()) {
			return $this->export(
				$builder->selectRaw('id, name, email')
			);
		}

		$customers = $builder->orderBy('id', 'desc')->paginate(session('per_page'));
		$customers->setPath('customers');

		return view('customers.index')->with([
			'page_title' => trans('app.customers'),
			'data' => $customers,
		]);
	}

	public function create()
	{
		return view('customers.create')->with([
			'page_title' => trans('app.customer') . ' | ' . trans('app.add_new'),
		]);
	}

	public function store()
	{
		$rules = [
			'name'				=> 'required',
			'email'				=> 'required|email|unique:users',
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('customers/create')
				->withErrors($validator)
				->withInput();
		}
		else {

			$User = new User();

			try {
				$customer = $User->createCustomer(Input::get('name'), Input::get('email'), Input::get('details'));
				if(!$customer) {
					flash()->error(trans('app.error'));
					return Redirect::to('customers/create')
						->withInput();
				}
				flash()->success(trans('app.success'));
				return Redirect::to('customers/'.$customer->id.'/edit');
			} catch (\Exception $e) {
				return Redirect::to('customers/create')
					->withErrors(Lang::has('app.'.$e->getMessage()) ? trans('app.'.$e->getMessage()) : $e->getMessage())
					->withInput();
			}
		}
	}

	public function edit($id)
	{
		$customer = User::with('transactions')->customers()->withTrashed()->findOrFail($id);
		$recent_downloads = Download::where('user_id', $customer->id)->orderBy('id', 'desc')->limit(5)->get();

		$plugins_customers_edit = '';
		$customers_edit = Event::fire(new ContentCustomersEdit($id));

		if($customers_edit) {
			foreach($customers_edit as $customers_edit_content) {
				$plugins_customers_edit .= $customers_edit_content;
			}
		}

		return view('customers.edit')->with([
			'page_title' => trans('app.customer') . ' | ' . trans('app.edit'),
			'customer' => $customer,
			'recent_downloads' => $recent_downloads,
			'plugins_customers_edit' => $plugins_customers_edit,
		]);
	}

	public function update($id)
	{
		$rules = [
			'name'		=> 'required',
			'email'		=> 'required|email',
			'password'		=> 'confirmed|min:'.config('global.min-password'),
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('customers/'.$id.'/edit')
				->withErrors($validator)
				->withInput();
		}
		else {
			$customer = User::customers()->withTrashed()->findOrFail($id);
			$customer->name = Input::get('name');
			$customer->email = Input::get('email');
			$customer->details = Input::get('details');

			if(Input::get('password')) {
				$customer->password = bcrypt(Input::get('password'));
			}

			try {
				$customer->save();
				if($customer->trashed()) {
					$customer->restore();
				}
				flash()->success(trans('app.success'));
				return Redirect::to('customers/'.$id.'/edit');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('customers/'.$id.'/edit')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

	public function destroy($id)
	{
		try {
			User::softDelete($id, url('customers/'.$id.'/edit'));
			flash()->success(trans('app.success'));
		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error').' '.$e->getMessage());
		}


		return Redirect::to('customers');
	}
}
