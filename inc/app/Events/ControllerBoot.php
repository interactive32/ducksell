<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ControllerBoot extends Event {

	use SerializesModels;

	public $controller;

	public function __construct($controller)
	{
		$this->controller = $controller;
	}

}
