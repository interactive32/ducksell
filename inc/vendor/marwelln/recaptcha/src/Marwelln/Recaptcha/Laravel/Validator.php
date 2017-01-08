<?php namespace Marwelln\Recaptcha\Laravel;

class Validator extends \Illuminate\Validation\Validator {
	public function validateRecaptcha($attribute, $value, $parameters) {
		return (new Model($value))->validate();
	}
}