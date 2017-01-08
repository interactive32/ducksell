<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class UserMetadata extends BaseModel {

    public $table = 'user_metadata';
    public $timestamps = false;
    public $guarded = [];


    public function addMetadata($user_id, array $data)
    {
        if(empty($data)){
            return false;
        }

        foreach($data as $key => $value) {
            $prepared[] = [
                'user_id' => $user_id,
                'key' => $key,
                'value' => $value,
                ];
        }

        return $this->insert($prepared);
    }

    public function updateMetaValue($user_id, $key, $value)
    {
        return $this->updateOrCreate(['user_id' => $user_id, 'key' => $key], ['value' => $value]);
    }

}
