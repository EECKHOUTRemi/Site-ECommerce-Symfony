<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/order", name="app_admin_orders_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminOrderController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/admin_order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Order $order): Response
    {
        return $this->render('admin/admin_order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
