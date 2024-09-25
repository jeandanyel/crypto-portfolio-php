<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Entity\Cryptocurrency;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use App\Handler\TransactionHandlerInterface;
use App\Repository\AssetRepository;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CryptocurrencyController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private AssetRepository $assetRepository
    )
    {
    }

    #[Route('/cryptocurrency', name: 'app_cryptocurrency')]
    public function index(TransactionHandlerInterface $transactionHandler): JsonResponse
    {
        /*$bitcoin = $this->cryptocurrencyRepository->findOneBy(['symbol' => 'BTC']);
        $etherum = $this->cryptocurrencyRepository->findOneBy(['symbol' => 'ETH']);

        dump($bitcoin, $etherum);

        $btcAsset = $this->assetRepository->findOneBy(['cryptocurrency' => $bitcoin]);
        $ethAsset = $this->assetRepository->findOneBy(['cryptocurrency' => $etherum]);
        

        dump($btcAsset, $ethAsset);*/

        // $crypto = new Cryptocurrency();

        // $crypto->setName('Solana');
        // $crypto->setSymbol('SOL');

        // $this->entityManager->persist($crypto);
        // $this->entityManager->flush();

        dump($this->cryptocurrencyRepository->findOneBy(['symbol' => 'SOL']));

        return $this->json([]);
    }
}
