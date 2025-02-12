<?php

namespace App\Validator;

use App\Entity\Transaction;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionAssetsValidator extends ConstraintValidator
{
    /**
     * Validates the assets of a Transaction object.
     * Ensures at least one of received_asset or transacted_asset is provided.
     * Checks that quantities are provided for any specified assets.
     * 
     * @param Transaction $transaction
     */
    public function validate(mixed $transaction, Constraint $constraint): void
    {
        if (!$transaction instanceof Transaction) {
            throw new UnexpectedTypeException($transaction, Transaction::class);
        }

        $transactedAsset = $transaction->getTransactedAsset();
        $transactedQuantity = $transaction->getTransactedQuantity();
        $receivedAsset = $transaction->getReceivedAsset();
        $receivedQuantity = $transaction->getReceivedQuantity();
        
        if (!$receivedAsset && !$transactedAsset) {
            $this->context->buildViolation("At least one of 'received_asset' or 'transacted_asset' must be provided.")
                ->addViolation();

            return;
        }

        if ($receivedAsset && !$receivedQuantity) {
            $this->context->buildViolation('The quantity for the received asset is missing.')
                ->atPath('receivedQuantity')
                ->addViolation();
        }

        if ($transactedAsset) {
            if (!$transactedQuantity) {
                $this->context->buildViolation('The quantity for the transacted asset is missing.')
                    ->atPath('transactedQuantity')
                    ->addViolation();
            }

            if ($transactedQuantity > $transactedAsset->getQuantity()) {
                $symbol = $transactedAsset->getCryptocurrency()->getSymbol();
                $message = sprintf(
                    'Insufficient balance. You requested %s %s, but only %s is available.',
                    $transactedQuantity,
                    strtoupper($symbol),
                    $transactedAsset->getQuantity(),
                );

                $this->context->buildViolation($message)
                    ->atPath('transactedQuantity')
                    ->addViolation();
            }
        }
    }
}
