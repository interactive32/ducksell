<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class EmailPurchase extends Event {

	use SerializesModels;

	public $hash;

	public function __construct($hash)
	{
		$this->hash = $hash;
	}

}
