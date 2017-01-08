<?php namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Option;
use App\Services\TemplateService;
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
class OptionController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$options = Option::all();

		return view('options.index')->with([
			'page_title' => trans('app.options'),
			'options' => $options,
		]);
	}

	public function update()
	{
		$rules = [
			'global_admin-mail' => 'required|email',
			'session_lifetime' => 'required|numeric|min:10',
			'global_min-password' => 'required|numeric',
			'global_invoice-offset' => 'required|numeric',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('options')
				->withErrors($validator)
				->withInput();
		} else {

			$templates = [
				'thankyou' => 'mail_template_thankyou',
				'generic' => 'mail_template_generic',
			];

			foreach (Input::except(['_token'] + $templates) as $key => $value) {

				try {
					Option::updateOrCreate(['key' => str_replace('_', '.', $key)], ['value' => $value]);
				} catch (\Exception $e) {
					Log::writeException($e);
					return Redirect::to('options')
						->withErrors($e->getMessage())
						->withInput();
				}
			}

			// templates are critical, try them first
			foreach($templates as $key => $value) {
				$TemplateService = new TemplateService();
				$TemplateService->loadSystemTemplate($key);
				$TemplateService->setContent(Input::get($value));
				if($TemplateService->render() === false) {
					return Redirect::to('options')
						->withErrors(trans('app.template_error'))
						->withInput();
				} else {
					Option::updateOrCreate(['key' => str_replace('_', '.', $value)], ['value' => Input::get($value)]);
				}
			}

			flash()->success(trans('app.success'));
			return Redirect::to('options');

		}
	}

	public function mailTest()
	{
		$TemplateService = new TemplateService();
		$TemplateService->loadSystemTemplate('generic');

		$ret = Util::sendMail(config('global.admin-mail'), trans('app.information'), $TemplateService->render());

		if(!$ret) {
			flash()->error(trans('app.error').', '.trans('app.check_logs'));
		} else {
			flash()->success(trans('app.mail_test_success'));
		}

		return Redirect::to('options');
	}

	public function templateSendMail($template)
	{
		$TemplateService = new TemplateService();
		$TemplateService->loadSystemTemplate($template);

		return Util::sendMail(config('global.admin-mail'), trans('app.information'), $TemplateService->render()) ? trans('app.mail_test_success') : trans('app.fail');
	}

}
