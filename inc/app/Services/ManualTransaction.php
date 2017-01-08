<?php namespace App\Services;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use App\Models\TransactionMetadata;

/**
 * Service
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class ManualTransaction {

    public $processor_currency = '';
    public $payment_processor = 'manual';
    public $admin;

    private $customer_id;
    private $product_id;
    private $amount;
    private $send_email;

    public function __construct($customer_id, $product_id, AmountService $amount, $send_email)
    {
        $this->customer_id = $customer_id;
        $this->product_id = $product_id;
        $this->amount = $amount;
        $this->send_email = $send_email ? true : false;
        $this->admin = Util::getCurrentUserInfo();
    }

    public function createOrder()
    {
        Log::write('log_manual_transaction', $this->admin);

        $Transaction = new Transaction();
        $ProductTransaction = new ProductTransaction();
        $TransactionMetadata = new TransactionMetadata();

        // create transaction
        $transaction = $Transaction->createTransaction($this->payment_processor, $this->customer_id, $this->amount, $Transaction::STATUS_APPROVED);

        if(!$transaction) {
           return false;
        }

        // add metadata
        $TransactionMetadata->addMetadata($transaction->id, $this->getTransactionMetaData());

        // add product
        $ProductTransaction->addProductToTransaction($this->product_id, $this->amount, $transaction->id);

        if($this->send_email) {
            $Transaction->sendPurchaseInformationEmail($transaction->hash);
        }

        return $transaction;
    }

    private function getTransactionMetaData()
    {
        $metadata = [];

        $timestamp = Carbon::create()->toDateTimeString();

        $metadata['sale_date_placed'] = $timestamp;
        $metadata['created_by'] = Util::getCurrentUserInfo();

        return $metadata;
    }
}
