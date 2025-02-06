<?php

namespace App\Serializer;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Entity\Asset;
use App\Entity\Transaction;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class TransactionDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AssetRepository $assetRepository,
        private Security $security,
        private IriConverterInterface $iriConverter
    ) {}

    public function denormalize($data, $class, $format = null, array $context = []): Transaction
    {
        $user = $this->security->getUser();

        foreach (['receivedAsset', 'transactedAsset'] as $assetProperty) {
            if (empty($data[$assetProperty])) {
                continue;
            }

            if (true /* TODO: validate if IRI is cryptocurrency */) {
                $cryptocurrency = $this->iriConverter->getResourceFromIri($data[$assetProperty]);
                $asset = $this->assetRepository->findOneBy([
                    'user' => $user,
                    'cryptocurrency' => $cryptocurrency
                ]);

                if (!$asset) {
                    $asset = new Asset();

                    $asset->setCryptocurrency($cryptocurrency);
                    $asset->setUser($user);

                    $this->entityManager->persist($asset);
                    $this->entityManager->flush();
                }

                $data[$assetProperty] = $this->iriConverter->getIriFromResource($asset);
            }
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
