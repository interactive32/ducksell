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
class TransactionProductAdded extends Event {

	use SerializesModels;

	public $product_transaction;

	public function __construct(Model $product_transaction)
	{
		$this->product_transaction = $product_transaction;
	}

}
