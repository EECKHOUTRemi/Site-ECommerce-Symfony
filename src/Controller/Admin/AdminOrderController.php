<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderConfirmationType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{id}", name="show", requirements={"id"="\d+"})
     */
    public function show(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        // Determine button labels and classes based on order status
        $formOptions = $this->getFormOptionsForStatus($order->getStatus());
        
        $form = $this->createForm(OrderConfirmationType::class, $order, $formOptions);
        $form->handleRequest($request);

        /** @var SubmitType $confirmButton  */
        $confirmButton = $form->get('confirm');

        /** @var SubmitType $cancelButton  */
        $cancelButton = $form->get('cancel');

        
        if ($confirmButton->isClicked()) {
            $this->handleConfirmAction($order);
            $em->flush();
            return $this->redirectToRoute('app_admin_orders_show', ['id' => $order->getId()]);
        } elseif ($cancelButton->isClicked()) {
            $order->setStatus(Order::STATUS_CANCELLED);
            $em->flush();
            return $this->redirectToRoute('app_admin_orders_show', ['id' => $order->getId()]);
        }

        

        return $this->render('admin/admin_order/show.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Get form options based on order status
     */
    private function getFormOptionsForStatus(string $status): array
    {
        $options = [
            'confirm_label' => 'Confirm Order',
            'cancel_label' => 'Cancel Order',
            'confirm_button_class' => 'btn btn-success',
        ];

        switch ($status) {
            case Order::STATUS_PENDING:
            case 'pending_confirmation':
                $options['confirm_label'] = 'Confirm Order';
                $options['confirm_button_class'] = 'btn btn-success';
                break;

            case Order::STATUS_CONFIRMED:
                $options['confirm_label'] = 'Mark as Shipped';
                $options['confirm_button_class'] = 'btn btn-primary';
                break;

            case Order::STATUS_SHIPPED:
                $options['confirm_label'] = 'Mark as Delivered';
                $options['confirm_button_class'] = 'btn btn-info';
                break;

            case Order::STATUS_DELIVERED:
                $options['confirm_label'] = 'Order Completed';
                $options['confirm_button_class'] = 'btn btn-secondary disabled';
                $options['cancel_label'] = 'Archive';
                break;

            case Order::STATUS_CANCELLED:
                $options['confirm_label'] = 'Reopen Order';
                $options['confirm_button_class'] = 'btn btn-warning';
                $options['cancel_label'] = 'Delete Order';
                break;
        }

        return $options;
    }

    /**
     * Handle the confirm action based on current status
     */
    private function handleConfirmAction(Order $order): void
    {
        switch ($order->getStatus()) {
            case Order::STATUS_PENDING:
            case 'pending_confirmation':
                $order->setStatus(Order::STATUS_CONFIRMED);
                
                break;

            case Order::STATUS_CONFIRMED:
                $order->setStatus(Order::STATUS_SHIPPED);
                break;

            case Order::STATUS_SHIPPED:
                $order->setStatus(Order::STATUS_DELIVERED);
                break;

            case Order::STATUS_CANCELLED:
                $order->setStatus(Order::STATUS_PENDING);
                break;
        }

        $order->setUpdatedAt(new \DateTime());
    }
}
