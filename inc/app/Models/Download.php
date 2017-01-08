<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Download extends BaseModel
{

    public function user()
    {
        return $this
            ->BelongsTo('App\Models\User')
            ->withTrashed();
    }
}
