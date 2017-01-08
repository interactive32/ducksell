<?php namespace App\Models;

use App\Events\ModelBoot;
use Config;
use DB;
use Event;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class BaseModel extends Model {

    public function __construct(array $attributes = array())
    {
        Event::fire(new ModelBoot($this, $attributes));

        parent::__construct($attributes);
    }

    public function scopeSearch($query, array $fields)
    {
        $search_term = Input::get('search_term', null);

        if(!$search_term || empty($fields)) {
            return $query;
        }

        $search_term = '%'.$search_term.'%';

        $params = [];
        $raw_query = '(';
        foreach($fields as $key => $field) {
            $params[] = $search_term;
            $raw_query .=  ($key > 0 ? ' or ' : '') . $field . ' like ?';
        }
        $raw_query .= ')';

        return $query
            ->whereRaw($raw_query, $params);
    }

    public static function softDelete($id, $restore_link)
    {
        if(!self::findOrFail($id)->delete()) {
            return false;
        }

        Log::write('log_record_deleted', trans('app.restore_link').': '.$restore_link, false, Log::TYPE_DELETE);

        return true;
    }

    public static function updateSchema()
    {
        $updated = false;

        if(config('global.schema') < 2.0) {

            $sql = "
            CREATE TABLE file_product (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `file_id` INT(11) NOT NULL,
              `product_id` INT(11) NULL,
              `weight` INT(11) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `index1` (`file_id` ASC, `product_id` ASC)
              );
            
            INSERT INTO file_product (file_id, product_id, weight)
            SELECT id, product_id, weight FROM files;
            
            ALTER TABLE files
            DROP COLUMN `weight`,
            DROP COLUMN `product_id`,
            DROP INDEX `index2`,
            ADD COLUMN `size` INT(11) NOT NULL AFTER `description`;
            ";

            $updated = self::runSchemaUpdate('2.0', $sql);
        }

        if(config('global.schema') < 3.0) {

            // int with not null must have default values in MySQL 5.7+
            $sql = "
            ALTER TABLE file_product
            CHANGE COLUMN `weight` `weight` INT(11) NOT NULL DEFAULT 0;
            
            ALTER TABLE users
            CHANGE COLUMN `role` `role` INT(11) NOT NULL DEFAULT 0 ;

            ";

            $updated = self::runSchemaUpdate('3.0', $sql);

        }

        if(config('global.schema') < 3.1) {

            $sql = "
            ALTER TABLE plugins
            ADD INDEX `index3` (`key` ASC);
            ";

            $updated = self::runSchemaUpdate('3.1', $sql);
        }



        return $updated;
    }

    static function runSchemaUpdate($version, $sql)
    {
        try {
            foreach (explode(';', $sql) as $line) {
                if(trim($line)) {
                    DB::statement($line);
                }
            }
        } catch (\Exception $e) {
            Log::write('DB Schema Update: failed!', $e->getMessage(), false, Log::TYPE_CRITICAL);
            return false;
        }

        Option::updateOrCreate(['key' => 'global.schema'], ['value' => $version]);
        Log::write('DB Schema Update: success', 'new version: '.$version);
        Config::set('global.schema', $version);

        return true;
    }

}
