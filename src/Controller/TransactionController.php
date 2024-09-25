<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\AssetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TransactionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AssetRepository $assetRepository
    )
    {
    }

    #[Route('/transaction/create', name: 'app_transaction_create')]
    public function create(Request $request): Response
    {
        $transaction = new Transaction();
        
        return $this->update($request, $transaction);
    }

    #[Route('/transaction/update/{id}', name: 'app_transaction_update')]
    public function update(Request $request, Transaction $transaction): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();
        }

        return $this->render('transaction/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/transaction/delete/{id}', name: 'app_transaction_delete')]
    public function delete(Transaction $transaction): Response
    {
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();

        return new Response();
    }
}
