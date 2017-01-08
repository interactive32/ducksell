<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ContentCustomersEdit extends Event {

	use SerializesModels;

	public $customer_id;

	public function __construct($customer_id)
	{
		$this->customer_id = $customer_id;
	}

}
