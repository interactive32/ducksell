<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class Product extends BaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function metadata()
    {
        return $this
            ->hasMany('App\Models\ProductMetadata');
    }

    public function files()
    {
        return $this
            ->BelongsToMany('App\Models\File')
            ->withPivot('weight')
            ->orderBy('weight');
    }

    public function transactions()
    {
        return $this
            ->BelongsToMany('App\Models\Transaction')
            ->withPivot('id', 'license_number', 'processor_amount', 'listed_amount', 'customer_amount');
    }

    public function getProductByExternalId($external_id)
    {
        return Product::where('external_id', $external_id)->first();
    }

    public function getPrice()
    {
        return $this->metadata->where('key', 'price')->first() ? $this->metadata->where('key', 'price')->first()->value : 0;
    }

    public function setPrice($cents)
    {
        $ProductMetadata = new ProductMetadata();

        $ProductMetadata->updateMetaValue($this->id, 'price', $cents);

        return true;
    }


}
