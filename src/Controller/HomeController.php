<?php

namespace App\Controller;

use App\Repository\RacquetRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     * @IsGranted("ROLE_USER")
     */
    public function index(RacquetRepository $racquetRepository): Response
    {
        // Get featured racquets (latest 6)
        $featuredRacquets = $racquetRepository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'featured_racquets' => $featuredRacquets,
        ]);
    }
}
