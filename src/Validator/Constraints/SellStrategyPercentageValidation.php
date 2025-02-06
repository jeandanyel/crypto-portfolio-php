<?php

namespace App\Validator\Constraints;

use App\Validator\SellStrategyPercentageValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class SellStrategyPercentageValidation extends Constraint
{
    public string $message = 'The total sell percentage for this asset cannot exceed 100%.';

    public function validatedBy(): string
    {
        return SellStrategyPercentageValidator::class;
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
