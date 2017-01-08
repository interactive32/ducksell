<?php namespace App\Http\Controllers;

use App\Models\Log;
use App\Services\Util;
use File;
use Input;
use Redirect;
use ZipArchive;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class PluginController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		return view('plugins.index')->with([
			'page_title' => trans('app.plugins'),
			'data' => $this->getPlugins(),
		]);
	}


	public function add()
	{
		return view('plugins.add')->with([
			'page_title' => trans('app.plugins'),
			'back_button' => ['route' => 'plugins', 'title' => trans('app.manage_plugins')],
		]);

	}

	public function upload()
	{

		if(Input::hasFile('file')) {

			// file upload
			$file_upload = Input::file('file');
			$target = $file_upload->move(config('global.tmp'), 'plugin_tmp.zip');

			try {
				$zip = new ZipArchive;
				if ($zip->open($target) === true) {
					$zip->extractTo(app_path('Plugins'));
					$zip->close();
					flash()->success(trans('app.success'));
				} else {
					flash()->error(trans('app.error'));
				}
				return Redirect::to('plugins');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('plugins')
					->withErrors($e->getMessage())
					->withInput();
			}

		} else {
			return Redirect::to('plugins')
				->withErrors(trans('app.select_file'))
				->withInput();
		}

	}

	public function toggle($key)
	{
		$plugins = $this->getPlugins();

		$plugin = $plugins[$key];

		if (file_exists($plugin->directory.'/disabled')) {
			unlink($plugin->directory.'/disabled');
		} else {
			touch($plugin->directory.'/disabled');
		}

		flash()->success(trans('app.success'));
		return Redirect::to('plugins');

	}

	public function remove($key)
	{
		$plugins = $this->getPlugins();

		$plugin = $plugins[$key];

		if(File::deleteDirectory($plugin->directory) && !file_exists($plugin->directory . '/init.php')) {
			flash()->success(trans('app.success'));
		} else {
			flash()->error(trans('app.error'));
		}

		return Redirect::to('plugins');
	}

	public function getPlugins()
	{
		$plugins = [];

		$key_cnt = 0;
		foreach (glob(app_path().'/Plugins/*') as $key => $plugin_dir) {

			if (file_exists($plugin_dir . '/init.php')) {
				$name = $version = $author = $description = '';
				require_once $plugin_dir . '/init.php';
				$plugin = new \stdClass();
				$plugin->key = $key_cnt;
				$plugin->directory = $plugin_dir;
				$plugin->name = $name;
				$plugin->version = $version;
				$plugin->author = $author;
				$plugin->description = $description;
				$plugin->enabled = file_exists($plugin_dir . '/disabled') ? false : true;
				$plugins[] = $plugin;
				++$key_cnt;
			}

		}

		return $plugins;
	}

}
