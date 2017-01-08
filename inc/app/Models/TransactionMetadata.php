<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class TransactionMetadata extends BaseModel {

    public $table = 'transaction_metadata';
    public $timestamps = false;
    public $guarded = [];


    public function addMetadata($transaction_id, array $data)
    {
        if(empty($data)){
            return false;
        }

        foreach($data as $key => $value) {
            $prepared[] = [
                'transaction_id' => $transaction_id,
                'key' => $key,
                'value' => $value,
                ];
        }

        return $this->insert($prepared);
    }

    public function updateMetaValue($transaction_id, $key, $value)
    {
        return $this->updateOrCreate(['transaction_id' => $transaction_id, 'key' => $key], ['value' => $value]);
    }

}
