What is reCAPTCHA?
=========

> reCAPTCHA is a free service to protect your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.

This package gives you an easy validation in PHP for Google's new (december 2014) reCAPTCHA. It allows you to validate if you are a human or robot with just a simple mouse click. See [this Youtube link](https://www.youtube.com/watch?v=jwslDn3ImM0&channel=GoogleWebmasterHelp) for a brief look at how it works.

# Installation

You can install this package by using [Composer](https://getcomposer.org/).

- Option 1: Add `"marwelln/recaptcha" : "~2.0"` to your `composer.json` file and run `composer update`.
- Option 2: Run `composer require marwelln/recaptcha:dev-master`

### Laravel

If you're using Laravel, you need to `'Marwelln\Recaptcha\RecaptchaServiceProvider'` to your `providers` array in `config/app.php`.

# Recaptcha keys

To use reCAPTCHA, you need to have a `site key` and a `secret key`. [Click here](https://www.google.com/recaptcha/admin#createsite) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from Google.

# Front-end usage

To display the reCAPTCHA widget, you first need to include their javascript file on your site. Put the following code at the bottom of your site.

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

After that, you need to add a `div` where you want to display the widget. The required attributes is `class` with a value of `g-recaptcha` and `data-sitekey` with a value of your site key. _Don't have a key? [Get one over here](https://www.google.com/recaptcha/admin#createsite)._

    <div class="g-recaptcha" data-sitekey="your_site_key"></div>

There are three optional attributes available for you to style and manage the widget. It's `data-theme`, `data-type` and `data-callback`. See Google's [configuration documentation](https://developers.google.com/recaptcha/docs/display#config) for up-to-date and more details about each attribute.

### Laravel

If your using Laravel, you can display the widget by including the available view file. This will include the script file and the div tag.

Laravel 5:

    {!! View::make('recaptcha::display') !!}
    
Laravel 4:

    {{ View::make('recaptcha::display') }}

# Back-end usage

To validate the response we get from Google we use the `Marwelln\Recaptcha\Model` class.

    $validator = new \Marwelln\Recaptcha\Model($_POST['g-recaptcha-response'], $secretKey);
    $validated = $validator->validate();

    $response = '';
    if ( ! $validated) {
        $response .= '<pre>' . print_r($validator->errors(), true) . '</pre>';
    }

    $response .= 'Did I validate? ' . ($validated ? 'Yes.' : 'No.');

    echo $response;

If you don't want to insert `$secretKey` everytime you want to validate the response, you can add an environment value with `RECAPTCHA_SECRETKEY` as key.

    putenv('RECAPTCHA_SECRETKEY=my-recaptcha-secret-key');

    $validator = new \Marwelln\Recaptcha\Model($_POST['g-recaptcha-response']);

### Laravel

Laravel validation is supported by using the `recaptcha` validation rule on `g-recaptcha-response`.

    $rules = [
        'g-recaptcha-response' => 'required|recaptcha'
    ];

    $validator = Validator::make(Input::all(), $rules);
    $validated = $validator->passes();

    $response = '';
    if ( ! $validated) {
        $response .= '<pre>' . print_r($validator->errors()->all(), true) . '</pre>';
    }

    $response .= 'Did I validate? ' . ($validated ? 'Yes.' : 'No.');

    return $response;

### Errors

If `validate()` fails, you can get the errors by calling `$validator->errors()`. This will return an array with codes errors keys. See _Error code reference_ at the [documenation website](https://developers.google.com/recaptcha/docs/verify).

If you are using **Laravel**, `errors()` will return an instance of `Illuminate\Support\MessageBag` with all errors translated.

# Laravel configuration

There is not really a need to publish the configuration file. Both the `siteKey` and `secretKey` should be set in your environment file so it won't be available in your versioning system.

The only option available is to enable or disable curl. So if you're having trouble or don't have access to curl, you can publish the configuration file and change the `curl` value to false. We will then use `file_get_contents` instead.

Laravel 5:

    php artisan vendor:publish
    
Laravel 4:

    php artisan config:publish marwelln/recaptcha

See [Protecting Sensitive Configuration](http://laravel.com/docs/4.2/configuration#protecting-sensitive-configuration) if you don't know how to setup environment variables in Laravel 4.
