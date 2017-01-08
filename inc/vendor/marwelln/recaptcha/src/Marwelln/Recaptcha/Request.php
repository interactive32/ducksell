<?php namespace Marwelln\Recaptcha;

class Request {
	/**
	 * @param str
	 */
	protected $url;

	/**
	 * @param str $url the recaptcha api request url
	 */
	public function __construct($url) {
		$this->url = $url;
	}

	/**
	 * Create a simple api request using file_get_contents.
	 *
	 * @return json
	 */
	protected function simple() {
		return json_decode(file_get_contents($this->url));
	}

	/**
	 * Create an api request using curl.
	 *
	 * @return json
	 */
	protected function curl() {
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		return $response;
	}

	/**
	 * Run the request and get response from Google.
	 *
	 * @return json
	 */
	public function run($curl = true) {
		return $curl === true ? $this->curl() : $this->simple();
	}
}