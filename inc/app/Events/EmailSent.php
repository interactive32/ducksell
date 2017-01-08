<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Swift_Message;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class EmailSent extends Event {

	use SerializesModels;

	public $email;

	public function __construct(Swift_Message $email)
	{
		$this->email = $email;
	}

}
