<?php

namespace App\EventListener;

use App\Entity\Security\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.request')]
class OnKernelRequestListener
{
	public function __construct(
		private EntityManagerInterface $entityManager,
		private Security $security
	) {}

	public function __invoke(): void
	{
		/** 
         * @var ?User 
         */
		$user = $this->security->getUser();

		if ($user) {
			$filter = $this->entityManager->getFilters()->enable('user_filter');

			$filter->setParameter('user_id', $user->getId());
		}
	}
}