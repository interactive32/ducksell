<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class PreCreateCustomer extends Event {

	use SerializesModels;

	public $name, $email, $details, $metadata;

	public function __construct(&$name, &$email, &$details, array &$metadata)
	{
		$this->name = &$name;
		$this->email = &$email;
		$this->details = &$details;
		$this->metadata = &$metadata;
	}

}
