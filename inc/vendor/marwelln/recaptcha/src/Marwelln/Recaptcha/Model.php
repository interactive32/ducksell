<?php namespace Marwelln\Recaptcha;

class Model {
	/**
	 * Googles api base request url. We will validate the recaptcha response with this url.
	 *
	 * @param str
	 */
	protected $baseUrl = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * API request url with secret key and response.
	 *
	 * @param str
	 */
	protected $url;

	/**
	 * The response key we got from recaptcha.
	 *
	 * @param str
	 */
	protected $response;

	/**
	 * The secret key we got from Google.
	 *
	 * @param str
	 */
	protected $secretKey;

	/**
	 * All errors will be added to the message bag.
	 *
	 * @param array|\Illuminate\Support\MessageBag
	 */
	protected $errors = [];

	/**
	 * @return array|\Illuminate\Support\MessageBag
	 */
	public function errors() {
		return $this->errors;
	}

	/**
	 * Initialize validator and set response key.
	 *
	 * @param $response str
	 */
	public function __construct($response, $secretKey = null) {
		$this->secretKey = $secretKey ?: getenv('RECAPTCHA_SECRETKEY');
		$this->response = $response;
	}

	/**
	 * Build the api request url with query parameters.
	 *
	 * @return $this
	 */
	protected function buildUrl() {
		$this->url = $this->baseUrl . '?' . http_build_query(['secret' => $this->secretKey, 'response' => $this->response]);
	}

	/**
	 * Validate the response we got from recaptcha.
	 *
	 * @return bool true if validation passes
	 */
	public function validate() {
		$this->buildUrl();

		$json = (new Request($this->url))->run();

		// If we got errors, add them to the errors property.
		if (isset($json->{"error-codes"})) {
			$this->errors = $json->{"error-codes"};
		}

		return (bool) $json->success;
	}
}