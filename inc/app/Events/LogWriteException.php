<?php namespace App\Events;

use Exception;
use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class LogWriteException extends Event {

	use SerializesModels;

	public $e;

	public function __construct(Exception $e)
	{
		$this->e = $e;
	}

}
