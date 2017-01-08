<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Plugin extends BaseModel {

    public $timestamps = false;
    public $guarded = [];


    public static function addData($plugin_name, array $data)
    {
        if(empty($data)){
            return false;
        }

        foreach($data as $key => $value) {
            $prepared[] = [
                'plugin_name' => $plugin_name,
                'key' => $key,
                'value' => $value,
                ];
        }

        return self::insert($prepared);
    }

    public static function updateValue($plugin_name, $key, $value)
    {
        return self::updateOrCreate(['plugin_name' => $plugin_name, 'key' => $key], ['value' => $value]);
    }

    public static function getData($plugin_name)
    {
        return self::where('plugin_name', $plugin_name)->get();
    }

}
