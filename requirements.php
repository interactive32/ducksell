<?php

if (! defined('APP_VERSION')) {
    echo "Error: No direct access. Please point your browser to index.php instead.";
    die;
}

// system requirements
$errors = [];

if (version_compare(PHP_VERSION, '5.4') < 0) $errors[] = 'Error: PHP 5.4 or above is required. Please upgrade your PHP version.';
if (!extension_loaded('zip')) $errors[] = 'Error: zip php extension not loaded.';
if (!extension_loaded('mcrypt')) $errors[] = 'Error: mcrypt php extension not loaded.';
if (!extension_loaded('openssl')) $errors[] = 'Error: openssl php extension not loaded.';
if (!extension_loaded('mbstring')) $errors[] = 'Error: mbstring php extension not loaded.';
if (!extension_loaded('tokenizer')) $errors[] = 'Error: tokenizer php extension not loaded.';
if (!extension_loaded('PDO')) $errors[] = 'Error: PDO php extension not loaded.';
if (!extension_loaded('pdo_mysql')) $errors[] = 'Error: pdo_mysql php extension not loaded.';
if (!file_exists(__DIR__ . '/.htaccess')) $errors[] = 'Error: configuration file not found: '.__DIR__.'/.htaccess';
if (!is_writable(__DIR__ . '/inc/app/Plugins')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/app/Plugins';
if (!is_writable(__DIR__ . '/inc/storage/')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/';
if (!is_writable(__DIR__ . '/inc/storage/app')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/app';
if (!is_writable(__DIR__ . '/inc/storage/files')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/files';
if (!is_writable(__DIR__ . '/inc/storage/logs')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/logs';
if (!is_writable(__DIR__ . '/inc/storage/tmp')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/tmp';
if (!is_writable(__DIR__ . '/inc/storage/framework/')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/framework/';
if (!is_writable(__DIR__ . '/inc/storage/framework/cache')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/framework/cache';
if (!is_writable(__DIR__ . '/inc/storage/framework/views')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/framework/views';
if (!is_writable(__DIR__ . '/inc/storage/framework/sessions')) $errors[] = 'Error: directory not writable: '.__DIR__.'/inc/storage/framework/sessions';

if(!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . '<br>';
    }

    echo 'Please consult with your hosting company or server admin';
    echo '<br>';
    die('Halted!');
}