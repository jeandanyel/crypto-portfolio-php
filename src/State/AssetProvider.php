<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Asset;
use App\Entity\Transaction;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Retrieves or creates an Asset entity for a user based on a cryptocurrency symbol (e.g., BTC).
 * If the Asset doesn't exist and the operation is a POST or PATCH, a new Asset is created.
 * 
 * @implements ProviderInterface<Asset>
 */
final class AssetProvider implements ProviderInterface
{
    public function __construct(
        private AssetRepository $assetRepository,
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?Asset
    {
        $rootOperation = $context['root_operation'] ?? null;
        $isTransactionOperation = $rootOperation?->getClass() === Transaction::class;
        $cryptocurrency = $this->cryptocurrencyRepository->find($uriVariables['cryptocurrencyId']);
        $user = $this->security->getUser();

        $asset = $this->assetRepository->findOneBy([
            'user' => $user,
            'cryptocurrency' => $cryptocurrency
        ]);

        if (!$asset && $isTransactionOperation && in_array(get_class($rootOperation), [Post::class, Patch::class])) {
            $asset = new Asset();

            $asset->setCryptocurrency($cryptocurrency);
            $asset->setUser($user);
        }

        return $asset;
    }
}
