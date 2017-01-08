<?php namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class LogWrite extends Event {

	use SerializesModels;

	public $log, $inform_admin;

	public function __construct(Model $log, $inform_admin)
	{
		$this->log = $log;
		$this->inform_admin = $inform_admin;
	}

}
