<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ContentTransactionsEdit extends Event {

	use SerializesModels;

	public $transaction_id;

	public function __construct($transaction_id)
	{
		$this->transaction_id = $transaction_id;
	}

}
