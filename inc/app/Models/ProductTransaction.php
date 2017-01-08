<?php namespace App\Models;

use App\Events\TransactionProductAdded;
use App\Services\AmountService;
use App\Services\Util;
use Event;

/**
 * Model
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ProductTransaction extends BaseModel {

    public $table = 'product_transaction';
    public $timestamps = false;
    public $guarded = [];

    public function product()
    {
        return $this
            ->belongsTo('App\Models\Product');
    }

    public function transaction()
    {
        return $this
            ->belongsTo('App\Models\Transaction')
            ->with('customer', 'products');
    }

    public function addProductToTransaction($product_id, AmountService $amount, $transaction_id)
    {
        $license_number = Util::generateLicenseNumber();

        $ret = $this->create([
            'product_id' => $product_id,
            'transaction_id' => $transaction_id,
            'license_number' => $license_number,
            'processor_amount' => $amount->getProcessorAmount(),
            'listed_amount' => $amount->getListedAmount(),
            'customer_amount' => $amount->getCustomerAmount(),
        ]);

        Event::fire(new TransactionProductAdded($ret));

        return $ret;
    }

    public static function getTransactionByLicenseNumber($license_number)
    {
        $ret = self::where('license_number', $license_number)->first();

        if(!$ret) {
            return false;
        }

        return $ret->transaction;
    }

    public static function getProductByLicenseNumber($license_number)
    {
        $ret = self::where('license_number', $license_number)->first();

        if(!$ret) {
            return false;
        }

        return $ret->product;
    }

    public static function getFileByLicenseNumber($license_number, $file_id)
    {
        $ret = self::where('license_number', $license_number)->first();

        if(!$ret || !$ret->product || $ret->product->files->isEmpty()) {
            return false;
        }

        return $ret->product->files->keyBy('id')->find($file_id);
    }
}
