<?php namespace App\Models;

use App\Events\TransactionUpdated;
use Event;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class TransactionUpdate extends BaseModel {

    public $guarded = [];

    public function updateTransaction($transaction_id, $description, $updated_by, $value = '', $raw_data = '')
    {
        $ret = $this->create([
            'transaction_id' => $transaction_id,
            'description' => $description,
            'value' => $value,
            'raw_data' => $raw_data,
            'updated_by' => $updated_by,
        ]);

        Event::fire(new TransactionUpdated($ret));

        return $ret;
    }

}
