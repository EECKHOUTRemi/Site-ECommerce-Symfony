<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDetailOrderController extends AbstractController
{
    /**
     * @Route("/admin/detail/order", name="app_admin_detail_order")
     */
    public function index(): Response
    {
        return $this->render('admin_detail_order/index.html.twig', [
            'controller_name' => 'AdminDetailOrderController',
        ]);
    }
}
