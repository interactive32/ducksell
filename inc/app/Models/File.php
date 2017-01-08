<?php namespace App\Models;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class File extends BaseModel {

    public $guarded = [];
    
    public function products()
    {
        return $this
            ->BelongsToMany('App\Models\Product')
            ->withPivot('weight');
    }

    public function addFile($file_name, $file_name_internal, $description = '')
    {
        try {
            $ret = $this->create([
                'file_name' => $file_name,
                'file_name_internal' => $file_name_internal,
                'description' => $description,
                'size' => filesize(config('global.file_path') . $file_name_internal),
            ]);

        } catch (\Exception $e) {
            Log::writeException($e);
            return false;
        }

        return $ret;
    }

}
