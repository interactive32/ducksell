<?php namespace App\Services;

/**
 * Service
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
class AmountService {

    public $processor;
    public $listed;
    public $customer;

    public function __construct()
    {

        $this->processor = new \stdClass();
        $this->processor->currency = config('global.default-manual-currency');
        $this->processor->amount = 0;

        $this->listed = new \stdClass();
        $this->listed->currency = config('global.default-manual-currency');
        $this->listed->amount = 0;

        $this->customer = new \stdClass();
        $this->customer->currency = config('global.default-manual-currency');
        $this->customer->amount = 0;

    }

    public function setProcessorCurrency($currency)
    {
        $this->processor->currency = $currency;
    }

    public function getProcessorCurrency()
    {
        return $this->processor->currency;
    }

    public function setProcessorAmount($amount)
    {
        $this->processor->amount = $amount;
    }

    public function getProcessorAmount($inCents = true)
    {
        return $inCents ? $this->processor->amount * 100 : $this->processor->amount;
    }

    public function setListedCurrency($currency)
    {
        $this->listed->currency = $currency;
    }

    public function getListedCurrency()
    {
        return $this->listed->currency;
    }

    public function getListedAmount($inCents = true)
    {
        return $inCents ? $this->listed->amount * 100 : $this->listed->amount;
    }

    public function setListedAmount($amount)
    {
        $this->listed->amount = $amount;
    }

    public function setCustomerCurrency($currency)
    {
        $this->customer->currency = $currency;
    }

    public function getCustomerCurrency()
    {
        return $this->customer->currency;
    }

    public function setCustomerAmount($amount)
    {
        $this->customer->amount = $amount;
    }

    public function getCustomerAmount($inCents = true)
    {
        return $inCents ? $this->customer->amount * 100 : $this->customer->amount;
    }

    public static function displayAmount($amount_cents, $currency = '')
    {
        return '<span class="amount">'.$currency . ($currency ? ' ' : '') . number_format(round($amount_cents / 100, 2), 2).'</span>';
    }
}
