<?php

namespace App\Handler;

use App\Entity\Transaction;

interface TransactionHandlerInterface {
    public function process(Transaction $transation): void;
    public function revert(Transaction $transation): void;
}