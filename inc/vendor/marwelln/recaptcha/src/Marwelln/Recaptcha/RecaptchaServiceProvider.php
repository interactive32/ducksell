<?php namespace Marwelln\Recaptcha;

use Illuminate\Support\ServiceProvider;
use Marwelln\Recaptcha\Laravel\Validator;

class RecaptchaServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../../config/config.php' => config_path('recaptcha.php'),
			__DIR__ . '/../../views/display.blade.php' => base_path('resources/views/vendor/recaptcha/display.blade.php'),
		]);

		$this->loadViewsFrom(__DIR__ . '/../../views', 'recaptcha');

		$this->app->validator->resolver(function($translator, $data, $rules, $messages) {
			return new Validator($translator, $data, $rules, $messages);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}


	/**
	 * Register a view file namespace.
	 *
	 * @param  string  $namespace
	 * @param  string  $path
	 * @return void
	 */
	protected function loadViewsFrom($path, $namespace)
	{
		if (is_dir($appPath = $this->app->basePath() . '/resources/views/vendor/' . $namespace))
		{
			$this->app['view']->addNamespace($namespace, $appPath);
		}

		$this->app['view']->addNamespace($namespace, $path);
	}

}
