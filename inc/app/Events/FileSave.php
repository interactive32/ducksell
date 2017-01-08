<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\File;

/**
 * Event
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class FileSave extends Event {

	use SerializesModels;

	public $file;

	public function __construct(File $file)
	{
		$this->file = $file;
	}

}
