<?php

namespace App\Controller;

use App\Repository\RacquetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Select2Controller extends AbstractController
{
    /**
     * @Route("/select2", name="app_select2")
     */
    public function index(): Response
    {
        return $this->render('select2/index.html.twig', [
            'controller_name' => 'Select2Controller',
        ]);
    }

    /**
     * @Route("/select2/submit", name="app_select2_submit", methods={"POST"})
     */
    public function submit(Request $request, RacquetRepository $racquetRepository): Response
    {
        $racquetId = $request->request->get('racquet_id');
        
        if ($racquetId) {
            $racquet = $racquetRepository->find($racquetId);
            
            if ($racquet) {
                return $this->render('select2/index.html.twig', [
                    'controller_name' => 'Select2Controller',
                    'selectedRacquet' => [
                        'id' => $racquet->getId(),
                    ]
                ]);
            }
        }
        
        return $this->redirectToRoute('app_select2');
    }
}
