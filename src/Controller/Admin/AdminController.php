<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="app_admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(new Expression(
            '"ROLE_USER_ADMIN" in role_names 
            or "ROLE_ORDER_ADMIN" in role_names 
            or "ROLE_RACQUET_ADMIN" in role_names'
        ));

        return $this->render('admin/index.html.twig');
    }
}
