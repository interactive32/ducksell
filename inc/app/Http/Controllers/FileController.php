<?php namespace App\Http\Controllers;

use App\Events\FileSave;
use App\Models\File;
use App\Models\Log;
use App\Models\Product;
use App\Services\Util;
use Event;
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
class FileController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$files = File::search(['file_name', 'file_name_internal'])->paginate(session('per_page'));
		$files->setPath('files');

		return view('files.index')->with([
			'page_title' => trans('app.files'),
			'data' => $files,
		]);
	}

	public function add()
	{

		$existing_files = glob(config('global.file_tmp') . '*');
		foreach ($existing_files as &$file) {
			$file = str_replace(config('global.file_tmp'), '', $file);
		}
		$existing_files = array_combine($existing_files, $existing_files);

		return view('files.add')->with([
			'page_title' => trans('app.files') . ' | ' . trans('app.add_new'),
			'back_button' => ['route' => '/files', 'title' => trans('app.files')],
			'existing_files' => $existing_files,
			'tmp_path' => config('global.file_tmp'),
		]);
	}

	public function store()
	{
		$file_name_internal = $file_name = false;
		
		if (Input::hasFile('file')) {
			$file_name = Input::file('file')->getClientOriginalName();
			$file_name_internal = Util::uploadFileFromInput(Input::file('file'));

		} elseif (Input::get('existing_file')) {
			$file_name = Input::get('existing_file');
			$file_name_internal = Util::getFileFromServer($file_name);
		}

		// error
		if(!$file_name_internal || !$file_name) {
			return Redirect::to('files/add')
				->withErrors(trans('app.select_file'))
				->withInput();
		}

		$File = new File();

		try {
			$file = $File->addFile($file_name, $file_name_internal, Input::get('description', ''));

			if(Input::get('product_id') && $product = Product::findOrFail(Input::get('product_id'))) {
				$file->products()->attach($product->id);
			}

			Event::fire(new FileSave($file));
			flash()->success(trans('app.success'));
			return Redirect::to('files');
		} catch (\Exception $e) {
			Log::writeException($e);
			return Redirect::to('files')
				->withErrors($e->getMessage())
				->withInput();
		}
	}

	public function edit($id)
	{
		$file = File::findOrFail($id);

		return view('files.edit')->with([
			'page_title' => trans('app.file') . ' | ' . trans('app.edit'),
			'back_button' => ['route' => '/files', 'title' => trans('app.files')],
			'file' => $file,
		]);
	}

	public function update($id)
	{
		$rules = [
			'file_name' => 'required'
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('files/'.$id.'/edit')
				->withErrors($validator)
				->withInput();
		} else {
			$file = File::findOrFail($id);
			$file->file_name = Input::get('file_name');
			$file->description = Input::get('description');

			try {
				$file->save();
				flash()->success(trans('app.success'));
				return Redirect::to('files');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('files/'.$id.'/edit')
					->withErrors($e->getMessage())
					->withInput();
			}
		}
	}

	public function destroy($id)
	{
		$file = File::findOrFail($id);

		// detach all products
		foreach ($file->products as $product) {
			$product->files()->detach($file->id);
		}

		try {
			// remove db record
			$file->delete();
			// remove file on disk
			unlink(config('global.file_path') . $file->file_name_internal);
			flash()->success(trans('app.success'));
		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error').' '.$e->getMessage());
		}

		return Redirect::to(URL::previous());
	}
}
