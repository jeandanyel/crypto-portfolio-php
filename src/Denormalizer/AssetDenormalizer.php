<?php

namespace App\Denormalizer;

use App\Entity\Asset;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AssetDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private AssetRepository $assetRepository,
        private Security $security,
    ) {}

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ?Asset
    {
        // Vérifier si le type est bien Asset
        if ($type !== Asset::class) {
            return null;
        }

        return $this->getAsset($data);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Asset::class && is_string($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Asset::class => true,
        ];
    }

    private function getAsset(string $symbol): Asset
    {
        $user = $this->security->getUser();
        $cryptocurrency = $this->cryptocurrencyRepository->findOneBySymbol($symbol);

        if (!$cryptocurrency) {
            throw new NotFoundHttpException("Cryptocurrency with the ticker symbol $symbol does not exist.");
        }

        $asset = $this->assetRepository->findOneBy([
            'user' => $user,
            'cryptocurrency' => $cryptocurrency
        ]);

        if (!$asset) {
            $asset = new Asset();

            $asset->setCryptocurrency($cryptocurrency);
            $asset->setUser($user);
        }

        return $asset;
    }
}
