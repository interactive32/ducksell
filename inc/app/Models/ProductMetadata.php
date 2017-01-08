<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ProductMetadata extends BaseModel {

    public $table = 'product_metadata';
    public $timestamps = false;
    public $guarded = [];


    public function addMetadata($product_id, array $data)
    {
        if(empty($data)){
            return false;
        }

        foreach($data as $key => $value) {
            $prepared[] = [
                'product_id' => $product_id,
                'key' => $key,
                'value' => $value,
                ];
        }

        return $this->insert($prepared);
    }

    public function updateMetaValue($product_id, $key, $value)
    {
        return $this->updateOrCreate(['product_id' => $product_id, 'key' => $key], ['value' => $value]);
    }
}
