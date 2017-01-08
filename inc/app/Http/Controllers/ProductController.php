<?php namespace App\Http\Controllers;

use App\Events\ContentProductsEdit;
use App\Events\FileSave;
use App\Http\Requests;
use App\Models\File;
use App\Models\FileProduct;
use App\Models\Log;
use App\Models\Product;
use App\Models\ProductMetadata;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use App\Services\Util;
use Carbon\Carbon;
use Event;
use Exception;
use Input;
use Redirect;
use Response;
use Validator;

/**
 * Controller
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ProductController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$products = Product::with('files')->search(['external_id', 'name', 'description'])->paginate(session('per_page'));
		$products->setPath('products');

		return view('products.index')->with([
			'page_title' => trans('app.products'),
			'data' => $products,
		]);
	}

	public function create()
	{
		return view('products.create')->with([
			'page_title' => trans('app.product') . ' | ' . trans('app.add_new'),
		]);
	}

	public function store()
	{

		$ProductMetadata = new ProductMetadata();

		$rules = [
			'external_id' => 'required|unique:products',
			'name' => 'required',
			'price' => 'required|numeric',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('products/create')
				->withErrors($validator)
				->withInput();
		} else {
			$product = new Product();
			$product->external_id = Input::get('external_id');
			$product->name = Input::get('name');
			$product->description = Input::get('description');

			try {
				$product->save();
				$product->setPrice(Input::get('price') * 100); // prices are stored in cents
				flash()->success(trans('app.success'));
				return Redirect::to('products/' . $product->id . '/edit');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('products/create')
					->withErrors($e->getMessage())
					->withInput();
			}

		}
	}

	public function edit($product_id)
	{
		$product = Product::withTrashed()->findOrFail($product_id);
		$recent_sales = ProductTransaction::with('transaction')->where('product_id', $product_id)->orderBy('id', 'desc')->limit(3)->get();

		$plugins_products_edit = '';
		$products_edit = Event::fire(new ContentProductsEdit($product_id));

		if($products_edit) {
			foreach($products_edit as $products_edit_content) {
				$plugins_products_edit .= $products_edit_content;
			}
		}

		return view('products.edit')->with([
			'page_title' => trans('app.product') . ' | ' . trans('app.edit'),
			'product' => $product,
			'recent_sales' => $recent_sales,
			'today_sales' => Transaction::getSalesAmountByCurrency(Carbon::create()->startOfDay(), null, $product_id),
			'today_sales_count' => Transaction::getSalesCount(Carbon::create()->startOfDay(), null, $product_id),

			'this_week_sales' => Transaction::getSalesAmountByCurrency(Carbon::create()->startOfWeek(), null, $product_id),
			'this_week_sales_count' => Transaction::getSalesCount(Carbon::create()->startOfWeek(), null, $product_id),

			'this_month_sales' => Transaction::getSalesAmountByCurrency(Carbon::create()->startOfMonth(), null, $product_id),
			'this_month_sales_count' => Transaction::getSalesCount(Carbon::create()->startOfMonth(), null, $product_id),

			'all_time_sales' => Transaction::getSalesAmountByCurrency(Carbon::create()->startOfCentury(), null, $product_id),
			'all_time_sales_count' => Transaction::getSalesCount(Carbon::create()->startOfCentury(), null, $product_id),

			'plugins_products_edit' => $plugins_products_edit,
		]);

	}

	public function update($id)
	{
		$rules = [
			'external_id' => 'required',
			'name' => 'required',
			'price' => 'required|numeric'
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('products/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			$product = Product::withTrashed()->findOrFail($id);
			$product->external_id = Input::get('external_id');
			$product->name = Input::get('name');
			$product->description = Input::get('description');

			try {
				$product->save();
				$product->setPrice(Input::get('price') * 100); // prices are stored in cents
				if($product->trashed()) {
					$product->restore();
				}
				flash()->success(trans('app.success'));
				return Redirect::to('products');
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('products/create')
					->withErrors($e->getMessage())
					->withInput();
			}

		}
	}

	public function destroy($id)
	{
		try {
			$product = Product::with('files')->findOrFail($id);

			foreach($product->files as $file) {
				$this->removeFileFromProduct($file->id, $product->id);
			}

			Product::softDelete($id, url('products/'.$id.'/edit'));
			flash()->success(trans('app.success'));

		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error') . ' ' . $e->getMessage());
		}

		return Redirect::to('products');
	}

	public function fileSort($product_id)
	{
		$order = Input::get('order', null);

		if($order) {
			$order_array = explode(',', $order);
			foreach($order_array as $key => $file_id) {
				try {
					$file = FileProduct::where('product_id', $product_id)->where('file_id', $file_id)->get()->first();
					$file->weight = $key;
					$file->save();
				} catch (\Exception $e) {
					Log::writeException($e);
					return Response::json(trans('app.error') . ' ' . $e->getMessage());
				}
			}
		}

		return Response::json(true);
	}

	public function files($product_id)
	{
		$Product = Product::find($product_id);

		return view('products.files')->with([
			'page_title' => trans('app.manage_files'),
			'back_button' => ['route' => 'products/'.$product_id.'/edit', 'title' => $Product->name],
			'product_id' => $product_id,
			'data' => $Product->files()->get(),
		]);
	}

	public function fileAdd($product_id)
	{
		$product = Product::find($product_id);
		$FileProduct= new FileProduct();

		$existing_files = glob(config('global.file_tmp') . '*');
		foreach ($existing_files as &$server_file) {
			$server_file = str_replace(config('global.file_tmp'), '', $server_file);
		}
		$existing_files = array_combine($existing_files, $existing_files);

		$files = File::all();
		// only files that are not already assigned
		$reuse_files = [];
		foreach ($files as $file){
			if($FileProduct->where('file_id', $file->id)->where('product_id', $product->id)->get()->count() == 0) {
				$reuse_files[$file->id] = $file->file_name;
			}
		}

		return view('products.file_add')->with([
			'page_title' => trans('app.file') . ' | ' . trans('app.add_new'),
			'back_button' => ['route' => 'products/'.$product_id.'/files', 'title' => $product->name .' | '. trans('app.manage_files')],
			'product_id' => $product_id,
			'existing_files' => $existing_files,
			'reuse_files' => $reuse_files,
			'tmp_path' => config('global.file_tmp'),
		]);
	}

	public function fileSave($product_id)
	{
		$Product = Product::find($product_id);
		$file_name_internal = $file_name = false;

		// reuse file?
		if (Input::get('reuse_file') && $file = File::find(Input::get('reuse_file'))) {

			try {
				$Product->files()->attach($file->id);
				flash()->success(trans('app.success'));
			} catch (\Exception $e) {
				Log::writeException($e);
				return Redirect::to('products/'.$product_id.'/files/add')
					->withErrors($e->getMessage())
					->withInput();
			}

			// early exit
			return Redirect::to('products/'.$product_id.'/files');
		}

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
			// create new file in DuckSell
			$file = $File->addFile($file_name, $file_name_internal);
			$Product->files()->attach($file->id);
			Event::fire(new FileSave($file));
			flash()->success(trans('app.success'));
			return Redirect::to('products/'.$product_id.'/files');
		} catch (\Exception $e) {
			Log::writeException($e);
			return Redirect::to('products/'.$product_id.'/files/add')
				->withErrors($e->getMessage())
				->withInput();
		}

	}

	public function fileRemove($product_id, $file_id)
	{
		try {
			$this->removeFileFromProduct($file_id, $product_id);
			flash()->success(trans('app.success'));
		} catch (\Exception $e) {
			Log::writeException($e);
			flash()->error(trans('app.error') . ' ' . $e->getMessage());
		}

		return Redirect::to('products/'.$product_id.'/files');
	}

	private function removeFileFromProduct($file_id, $product_id)
	{
		$file = File::findOrFail($file_id);
		$Product = Product::findOrFail($product_id);

		$Product->files()->detach($file->id);

		return;
	}


	public function download($file_id)
	{
		$file = File::where('id', $file_id)->first();

		if(!$file) {
			return Redirect::to('/');
		}

		return Response::download(config('global.file_path') . $file->file_name_internal, $file->file_name);
	}

}
