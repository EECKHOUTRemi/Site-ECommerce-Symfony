<?php

namespace App\Controller;

use DateTime;
use App\Entity\Racquet;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\RacquetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_USER")
 */
class RacquetController extends AbstractController
{

    /**
    * @Route("/racquets", name="racquets")
    */
    public function racquets(RacquetRepository $racquetRepository): Response
    {
        return $this->render('racquet/index.html.twig', [
            'racquets' => $racquetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/racquet/{id}", name="racquet_detail")
     */
    public function detail(Racquet $racquet, Request $request, CartManager $cartManager)
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $data->setRacquet($racquet);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addRacquet($data)
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser());

            $cartManager->save($cart);

            return $this->redirectToRoute('racquet_detail', ['id' => $racquet->getId()]);
        }

        return $this->render('racquet/detail.html.twig', [
            'racquet' => $racquet,
            'form' => $form->createView()
        ]);
    }
}
