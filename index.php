<?php

require __DIR__.'/version.php';

require __DIR__ . '/requirements.php';

if (!file_exists(__DIR__ . '/inc/.env')) {
	echo 'Error: configuration file not found: '.__DIR__.'/inc/.env';
	die;
}

// continue with laravel...
require __DIR__.'/inc/bootstrap/autoload.php';
$app = require_once __DIR__.'/inc/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
