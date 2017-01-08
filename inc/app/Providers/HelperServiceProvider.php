<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ServiceProvider
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class HelperServiceProvider extends ServiceProvider {

	public function boot()
	{
		//
	}

	public function register()
	{
        foreach (glob(base_path().'/resources/helpers/*.php') as $filename){
            require_once($filename);
        }
	}

}
