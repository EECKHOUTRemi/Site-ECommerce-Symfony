<?php

namespace App\Controller;

use DateTime;
use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cart", name="app_cart_")
 * @IsGranted("ROLE_USER")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentCart();

        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $cart->setUpdatedAt(new \DateTime());
            $cart->setUser($this->getUser());
            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(CartManager $cartManager){
        $cart = $cartManager->getCurrentCart();

        if ($cart->getRacquets()->isEmpty()) {
            $this->addFlash('error', 'Your cart is empty. Please add items before checkout.');
            return $this->redirectToRoute('app_cart_index');
        }

        $cartManager->setPendingStatus($cart);
        
        return $this->render('cart/checkout.html.twig', [
            'order' => $cart
        ]);
    }
}
