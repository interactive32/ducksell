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
class PrintInvoice extends Event {

	use SerializesModels;

	public $transaction, $currency;

	public function __construct(Model $transaction, $currency)
	{
		$this->transaction = $transaction;
		$this->currency = $currency;
	}

}
