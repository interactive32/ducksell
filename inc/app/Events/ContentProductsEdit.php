<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ContentProductsEdit extends Event {

	use SerializesModels;

	public $product_id;
	
	public function __construct($product_id)
	{
		$this->product_id = $product_id;
	}

}
