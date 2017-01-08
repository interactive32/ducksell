<?php namespace App\Http\Middleware;

use App\Events\Options;
use App\Models\BaseModel;
use App\Models\Option;
use App\Services\Util;
use Closure;
use Config;
use Event;

/**
 * Middleware
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class SetOptions {

	public function handle($request, Closure $next)
	{
		$options = Option::all();

		// override laravel options with those from database
		foreach ($options as $option) {
			Config::set($option->key, $option->value);
		}

		if(BaseModel::updateSchema()) {
			return redirect()->to('');
		}

		if(!config('global.base-url')) {
			Option::updateOrCreate(['key' => 'global.base-url'], ['value' => url()]);
			Config::set('global.base-url', url());
		}

		if(!session('per_page')) {
			session(['per_page' => 10]);
		}

		app()->setLocale(config('global.locale'));

		if(mt_rand(1, 100) == 1) {
			Util::deleteOldTmpFiles();
		}

		Event::fire(new Options($request));

		return $next($request);
	}

}
