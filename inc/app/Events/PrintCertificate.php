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
class PrintCertificate extends Event {

	use SerializesModels;

	public $transaction, $product;

	public function __construct(Model $transaction, Model $product)
	{
		$this->transaction = $transaction;
		$this->product = $product;
	}

}
