<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class EmailPrepare extends Event {

	use SerializesModels;

	public $to, $subject, $body, $cc, $bcc;

	public function __construct(&$to, &$subject, &$body, &$cc = false, &$bcc = false)
	{
		$this->to = &$to;
		$this->subject = &$subject;
		$this->body = &$body;
		$this->cc = &$cc;
		$this->bcc = &$bcc;
	}

}
