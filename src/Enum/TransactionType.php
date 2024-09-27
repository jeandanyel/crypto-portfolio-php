<?php

namespace App\Enum;

enum TransactionType: string
{
    case BUY = 'buy';
    case SELL = 'sell';
    case SEND = 'send';
    case RECEIVE = 'receive';
    case TRANSFER = 'transfer';
    case TRADE = 'trade';
    case SWAP = 'swap';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}