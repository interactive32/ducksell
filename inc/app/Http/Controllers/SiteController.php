<?php namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Site;
use App\Services\Util;
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
class SiteController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$sites = Site::search(['name'])->paginate(session('per_page'));
		$sites->setPath('sites');

		return view('sites.index')->with([
			'page_title' => trans('app.sites'),
			'data' => $sites,
		]);
	}

	public function create()
	{
		return view('sites.create')->with([
			'page_title' => trans('app.site') . ' | ' . trans('app.add_new'),
		]);
	}

	public function store()
	{
		$rules = [
			'name'		=> 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('sites/create')
				->withErrors($validator)
				->withInput();
		}
		else {
			$site = new Site();
			$site->name = Input::get('name');

			try {
				$site->save();
				flash()->success(trans('app.success'));
				return Redirect::to('sites/'.$site->id.'/edit');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('sites/create')
					->withErrors($e->getMessage())
					->withInput();
			}

		}
	}

	public function edit($id)
	{
		$url = url();

		if(config('global.ssl-tracking')) {
			$url = '//'.Util::stripProtocol($url);
		}

		$url_script = $url . '/beacon.js';
		$url_noscript = $url . '/beacon?site_id=' . $id;

		$tracking_code = <<<EOT
<!-- DuckSell Analytics v1.0 -->
<script type="text/javascript">
	var _daq = _daq || [];
	(function() {
		var u="$url_script";
		_daq.push(['site_id', $id]);
		var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		g.type='text/javascript'; g.async=true; g.src=u; s.parentNode.insertBefore(g,s);
	})();
</script>
<noscript><p><img src="$url_noscript" style="border:0;" alt="" /></p></noscript>
<!-- End DuckSell Analytics Code -->
EOT;

		return view('sites.edit')->with([
			'page_title' => trans('app.site') . ' | ' . trans('app.edit'),
			'site' => Site::withTrashed()->findOrFail($id),
			'tracking_code' => $tracking_code,
		]);
	}

	public function update($id)
	{
		$rules = [
			'name'		=> 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {
			return Redirect::to('sites/'.$id.'/edit')
				->withErrors($validator)
				->withInput();
		}
		else {
			$site = Site::withTrashed()->findOrFail($id);
			$site->name = Input::get('name');

			try {
				$site->save();
				if($site->trashed()) {
					$site->restore();
				}
				flash()->success(trans('app.success'));
				return Redirect::to('sites');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('sites/create')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

	public function destroy($id)
	{
		try {
			Site::softDelete($id, url('sites/'.$id.'/edit'));
			flash()->success(trans('app.success'));
		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error').' '.$e->getMessage());
		}

		return Redirect::to('sites');
	}
}
