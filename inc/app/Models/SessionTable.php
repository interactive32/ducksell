<?php namespace App\Models;

use Session;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class SessionTable extends BaseModel {

	protected $table = 'sessions';
	public $timestamps = false;

	public function getActiveUsersData($time_ago_min)
	{
		$builder =
			$this
				->where('last_activity', '>', time() - $time_ago_min * 60)
				->where('sessions.id', '!=', Session::getId())
				->select('sessions.payload')
				;

		return $builder->get();
	}

}
