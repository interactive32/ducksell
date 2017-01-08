<?php

Route::group(['middleware' => ['auth', 'csrf']], function()
{
	Route::get('/', 'DashboardController@dashboard');
	Route::get('/home', 'DashboardController@dashboard');
	Route::get('getdata', 'DashboardController@getdata');

	Route::get('reports', 'ReportsController@analytics');
	Route::get('conversions', 'ReportsController@conversions');

	Route::get('customers/create', 'CustomerController@create');
	Route::post('customers/create', 'CustomerController@store');
	Route::get('customers/{id}/edit', 'CustomerController@edit');
	Route::post('customers/{id}/edit', 'CustomerController@update');
	Route::post('customers/{id}/destroy', 'CustomerController@destroy');
	Route::get('customers', 'CustomerController@index');

	Route::get('transactions/create/{user_id}', 'TransactionController@create');
	Route::post('transactions/create/{user_id}', 'TransactionController@store');
	Route::get('transactions/{id}/edit', 'TransactionController@edit');
	Route::post('transactions/{id}/edit', 'TransactionController@update');
	Route::get('transactions', 'TransactionController@index');

	Route::get('profile', 'ProfileController@edit');
	Route::post('profile', 'ProfileController@update');
	Route::get('profile/per_page/{num}', 'ProfileController@setPerPage');

	Route::get('products/create', 'ProductController@create');
	Route::post('products/create', 'ProductController@store');
	Route::get('products/{id}/edit', 'ProductController@edit');
	Route::post('products/{id}/edit', 'ProductController@update');
	Route::post('products/{id}/destroy', 'ProductController@destroy');
	Route::get('products/{id}/files', 'ProductController@files');
	Route::get('products/{id}/files/add', 'ProductController@fileAdd');
	Route::post('products/{id}/files/add', 'ProductController@fileSave');
	Route::get('products/{id}/files/sort', 'ProductController@FileSort');
	Route::post('products/{id}/files/{file}/remove', 'ProductController@fileRemove');
	Route::get('products/download/{file}', 'ProductController@download');
	Route::get('products', 'ProductController@index');

	Route::get('files', 'FileController@index');
	Route::post('files/{id}/destroy', 'FileController@destroy');
	Route::get('files/add', 'FileController@add');
	Route::post('files/add', 'FileController@store');
	Route::get('files/{id}/edit', 'FileController@edit');
	Route::post('files/{id}/edit', 'FileController@update');

});

Route::group(['middleware' => ['customer', 'csrf']], function()
{
	Route::get('download', 'DownloadController@index');
	Route::get('download/certificate/{license_number}', 'DownloadController@printCertificate');
	Route::get('download/invoice/{license_number}', 'DownloadController@printInvoice');
	Route::get('download/file/{license_number}/{file_id}', 'DownloadController@downloadFile');

});

Route::group(['middleware' => ['auth', 'csrf', 'admin']], function()
{
	Route::get('profiles/create', 'ProfilesController@create');
	Route::post('profiles/create', 'ProfilesController@store');
	Route::get('profiles/{id}/edit', 'ProfilesController@edit');
	Route::post('profiles/{id}/edit', 'ProfilesController@update');
	Route::post('profiles/{id}/destroy', 'ProfilesController@destroy');
	Route::get('profiles', 'ProfilesController@index');

	Route::get('options', 'OptionController@index');
	Route::post('options', 'OptionController@update');
	Route::get('options/mailtest', 'OptionController@mailTest');
	Route::get('options/templatetest/{template}', 'OptionController@templateSendMail');

	Route::get('plugins', 'PluginController@index');
	Route::post('plugin/toggle/{key}', 'PluginController@toggle');
	Route::post('plugin/remove/{key}', 'PluginController@remove');
	Route::get('plugin/add', 'PluginController@add');
	Route::post('plugin/add', 'PluginController@upload');

	Route::get('sites/create', 'SiteController@create');
	Route::post('sites/create', 'SiteController@store');
	Route::get('sites/{id}/edit', 'SiteController@edit');
	Route::post('sites/{id}/edit', 'SiteController@update');
	Route::post('sites/{id}/destroy', 'SiteController@destroy');
	Route::get('sites', 'SiteController@index');

	Route::get('logs', 'ReportsController@logs');
	Route::post('logs/purge', 'ReportsController@purgeLogs');
});

Route::group(['middleware' => ['csrf']], function()
{
	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
	]);
});

Route::get('beacon', 'BeaconController@beacon');
Route::get('beacon.js', 'BeaconController@javascript');

// plugins can add routes here
Event::fire(new \App\Events\Routes());
