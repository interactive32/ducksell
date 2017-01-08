<?php

/**
 * View Helper
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
function hasExpired(\App\Models\Transaction $transaction) {

    return \App\Models\Transaction::hasExpired($transaction);

}
