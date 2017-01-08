<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class DownloadFile extends Event {

	use SerializesModels;

	public $license_id, $file;

	public function __construct($license_id, $file)
	{
		$this->license_id = $license_id;
		$this->file = $file;
	}

}
