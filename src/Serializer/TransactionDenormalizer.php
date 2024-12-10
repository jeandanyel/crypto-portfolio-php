<?php

namespace App\Serializer;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Entity\Asset;
use App\Entity\Transaction;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class TransactionDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private AssetRepository $assetRepository,
        private Security $security,
        private IriConverterInterface $iriConverter
    ) {}

    public function denormalize($data, $class, $format = null, array $context = []): Transaction
    {
        foreach (['receivedAsset', 'transactedAsset'] as $assetProperty) {
            if (empty($data[$assetProperty])) {
                continue;
            }

            $data[$assetProperty] = $this->iriConverter->getIriFromResource(resource: Asset::class, context: [
                'uri_variables' => ['cryptocurrencyId' => $data[$assetProperty]]
            ]);
        }

        return $this->denormalizer->denormalize($data, $class, $format, $context + [__CLASS__ => true]);
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        $formatIsJson = in_array($format, ['json', 'jsonld'], true);
        $dataHasAssets = !empty($data['receivedAsset']) || !empty($data['transactedAsset']);
        $typeIsTransaction = is_a($type, Transaction::class, true);

        return $formatIsJson && $typeIsTransaction && $dataHasAssets && !isset($context[__CLASS__]);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Transaction::class => true
        ];
    }
}
