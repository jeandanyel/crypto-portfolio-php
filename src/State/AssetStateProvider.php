<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Asset;
use App\Repository\AssetRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AssetStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        private AssetRepository $assetRepository
    )
    {
    }

    /**
     * @return Asset[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->assetRepository->findAllWithPositiveQuantity();
        }

        return $this->itemProvider->provide($operation, $uriVariables, $context);
    }
}
