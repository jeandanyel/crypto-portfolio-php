<?php

namespace App\EventListener;

use App\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Asset::class)]
class AssetPrePersist
{
    public function __construct(private Security $security) {}

    public function __invoke(Asset $asset, PrePersistEventArgs $event): void
    {
        $asset->setUser($this->security->getUser());
    }
}
