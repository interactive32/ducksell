<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Site extends BaseModel {

	use SoftDeletes;

	protected $dates = ['deleted_at'];

}
