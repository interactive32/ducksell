<?php namespace Marwelln\Recaptcha\Laravel;

use Config, Lang;
use Illuminate\Support\MessageBag;
use Marwelln\Recaptcha\Request;

class Model extends \Marwelln\Recaptcha\Model {
	public function __construct($response, $secretKey = null) {
		parent::__construct($response, $secretKey ?: Config::get('recaptcha.secretKey'));

		$this->errors = new MessageBag;
	}

	/**
	 * Validate the response we got from recaptcha.
	 *
	 * @return bool true if validation passes
	 */
	public function validate() {
		$this->buildUrl();

		$json = (new Request($this->url))->run(Config::get('recaptcha.curl'));

		// If we got errors, add them to the errors property.
		if (isset($json->{"error-codes"})) {
			foreach ($json->{"error-codes"} as $key) {
				$this->errors->add($key, Lang::get('recaptcha::validator.' . $key));
			}
		}

		return (bool) $json->success;
	}
}

