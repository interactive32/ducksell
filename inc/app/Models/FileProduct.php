<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class FileProduct extends BaseModel {

    public $table = 'file_product';
    public $timestamps = false;
    public $guarded = [];

    public function product()
    {
        return $this
            ->belongsTo('App\Models\Product');
    }

    public function file()
    {
        return $this
            ->belongsTo('App\Models\File');
    }

    public function addFileToProduct($file_id, $product_id)
    {

        $ret = $this->create([
            'file_id' => $file_id,
            'product_id' => $product_id,
            'weight' => 0,
        ]);

        return $ret;
    }

}
