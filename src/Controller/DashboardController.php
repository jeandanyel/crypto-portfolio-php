<?php

namespace App\Controller;

use App\Repository\AssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AssetRepository $assetRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'assets' => $assetRepository->findAll()
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(AssetRepository $assetRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'assets' => $assetRepository->findAll()
        ]);
    }
}
