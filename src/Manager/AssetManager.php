<?php

namespace App\Manager;

use App\Entity\Asset;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

class AssetManager implements AssetManagerInterface
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * Calculates the total investment and average buy price for the given asset.
     */
    public function calculateInvestment(Asset $asset): void
    {
        $totalInvested = $this->transactionRepository->getTotalInvestedForAsset($asset);
        $averageBuyPrice = $this->transactionRepository->getAverageBuyPriceForAsset($asset);

        $asset->setTotalInvested($totalInvested);
        $asset->setAverageBuyPrice($averageBuyPrice);

        $this->entityManager->persist($asset);
        $this->entityManager->flush();
    }
}
