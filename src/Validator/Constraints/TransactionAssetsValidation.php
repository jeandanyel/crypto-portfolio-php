<?php

namespace App\Validator\Constraints;

use App\Validator\TransactionAssetsValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class TransactionAssetsValidation extends Constraint
{
    public string $message = 'At least one valid asset must be included in the transaction.';

    public function validatedBy(): string
    {
        return TransactionAssetsValidator::class;
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
