<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Option extends BaseModel {

	public $timestamps = false;
	public $primaryKey = 'key';
	public $fillable = ['key', 'value'];

}
