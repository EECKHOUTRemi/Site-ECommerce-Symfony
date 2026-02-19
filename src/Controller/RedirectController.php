<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController{

    /**
    * @Route("/", name="app_index")
    */
    public function index() : RedirectResponse{
        if ($this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_home");
        }
        return $this->redirectToRoute("app_login");
    }
}