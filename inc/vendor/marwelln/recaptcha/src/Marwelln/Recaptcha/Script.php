<?php namespace Marwelln\Recaptcha;

class Script {
	/**
	 * @param static
	 */
	protected static $instance;

	/**
	 * Decides if we've already loaded the script file or not.
	 *
	 * @param bool
	 */
	protected static $loaded = false;

	/**
	 * Make sure we can only initialize the class from the instance method.
	 */
	protected function __construct() {}

	/**
	 * Call this method to get singleton
	 *
	 * @return UserFactory
	 */
	public static function instance() {
		if ( ! isset(static::$instance)) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Print out the script.
	 */
	public function script() {
		if (static::$loaded) return '';

		static::$loaded = true;

		return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
	}
}