<?php 

namespace App\Form\DataTransformer;

use App\Entity\Asset;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Symfony\Component\Form\DataTransformerInterface;

class StringToAssetTransformer implements DataTransformerInterface
{
    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private AssetRepository $assetRepository
    ) {
    }

    /**
     * Transforms an object (asset) to a string (cryptocurrency's symbol).
     *
     * @param  Asset|null $asset
     */
    public function transform($asset): ?string
    {
        if (!$asset) {
            return null;
        }

        return $asset->getCryptocurrency()->getSymbol();
    }

    /**
     * Transforms a string (cryptocurrency's symbol) to an object (asset).
     *
     * @param  string $symbol
     */
    public function reverseTransform($symbol): ?Asset
    {
        if (!$symbol) {
            return null;
        }

        $cryptocurrency = $this->cryptocurrencyRepository->findOneBy(['symbol' => $symbol]);
        $asset = $this->assetRepository->findOneBy(['cryptocurrency' => $cryptocurrency]);

        if (!$asset) {
            $asset = new Asset();

            $asset->setCryptocurrency($cryptocurrency);
        }

        return $asset;
    }
}