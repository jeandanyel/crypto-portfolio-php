<?php

namespace App\Validator;

use App\Entity\Asset;
use App\Entity\SellStrategy;
use App\Validator\Constraints\SellStrategyPercentageValidation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SellStrategyPercentageValidator extends ConstraintValidator
{
    /**
     * Validates that the total sell percentage for a given asset does not exceed 100%.
     * 
     * This validator checks all existing SellStrategy records for the given asset
     * and ensures that the sum of their percentages, including the new one, remains 
     * within the allowed limit.
     * 
     * @param SellStrategy $sellStrategy
     */
    public function validate(mixed $asset, Constraint $constraint): void
    {
        if (!$asset instanceof Asset) {
            throw new UnexpectedTypeException($asset, Asset::class);
        }

        if (!$constraint instanceof SellStrategyPercentageValidation) {
            throw new UnexpectedTypeException($constraint, SellStrategyPercentageValidation::class);
        }

        $totalPercentage = 0;

        foreach ($asset->getSellStrategies() as $assetSellStrategy) {
            $totalPercentage += $assetSellStrategy->getPercentage();
        }

        if ($totalPercentage > 100) {
            $this->context->buildViolation($constraint->message)
                ->atPath('sellStrategies')
                ->addViolation();
        }
    }
}
