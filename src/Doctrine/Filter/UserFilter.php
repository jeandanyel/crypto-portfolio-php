<?php

namespace App\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
	public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
	{
		$userId = $this->hasParameter('user_id') ? $this->getParameter('user_id') : null;

		if ($targetEntity->hasAssociation('user') && $userId) {
			return sprintf('%s.user_id = %s', $targetTableAlias, $userId);
		}

		return '';
	}
}