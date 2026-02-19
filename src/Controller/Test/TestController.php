<?php

namespace App\Controller\Test;

use App\Form\Test\TestType;
use index;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     * IsGranted("ROLE_DEVELOPER")
     */
    public function index(): Response
    {
        $form = $this->createForm(TestType::class);
        return $this->render('test/index.html.twig', ['form' => $form->createView()]);
    }
}
