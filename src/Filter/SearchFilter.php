<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

final class SearchFilter extends AbstractFilter
{
    protected function filterProperty(string $property, mixed $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ('search' !== $property || null === $value) {
            return;
        }

        $value = strtolower($value);
        $properties = $this->getProperties();

        if (!$properties) {
            throw new \LogicException(sprintf('No properties configured for filter %s.', static::class));
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $conditions = [];

        foreach (array_keys($properties) as $property) {
            $conditions[] = sprintf('LOWER(o.%s) LIKE :%s', $property, $parameterName);
        }

        $queryBuilder
            ->andWhere(sprintf('(%s)', implode(' OR ', $conditions)))
            ->andWhere('o.coinMarketCapId IS NOT NULL AND o.coinGeckoId IS NOT NULL')
            ->setParameter($parameterName, "%$value%");
    }

    public function getDescription(string $resourceClass): array
    {
        $columns = array_keys($this->getProperties());
        $description = sprintf('Search across the following columns: %s.', implode(', ', $columns));

        return [
            'search' => [
                'property' => null,
                'type' => 'string',
                'required' => false,
                'description' => $description,
            ],
        ];
    }
}
