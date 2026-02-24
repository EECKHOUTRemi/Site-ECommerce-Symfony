<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\Cart\CartPromoCodeType;
use App\Form\Cart\CartType;
use App\Manager\CartManager;
use App\Repository\PromoCodeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="app_cart_")
 * @IsGranted("ROLE_USER")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(
        CartManager $cartManager, 
        Request $request, 
        PromoCodeRepository $promoCodeRepository,
        EntityManagerInterface $em
        ): Response
    {
        $cart = $cartManager->getCurrentCart();

        $formCart = $this->createForm(CartType::class, $cart);
        $formCart->handleRequest($request);

        $formPromo = $this->createForm(CartPromoCodeType::class);
        $formPromo->handleRequest($request);

        if ($cart->getPromoCode() && ($cart->getTotalWithoutDiscount() - $cart->getTotal()) == 0) {
            $cartManager->handlePromoCode($cart->getPromoCode()->getName(), $cart);
        }
        
        if ($formPromo->isSubmitted() && $formPromo->isValid()){
            $promoCode = $formPromo->get('name')->getData();
            if ($promoCodeRepository->findOneBy(['name' => $promoCode])) {
                $cartManager->handlePromoCode($promoCode, $cart);
            } else {
                $this->addFlash('error', 'Wrong code. Please enter a valid code.');
            }

            return $this->redirectToRoute('app_cart_index');
        }

        if ($formCart->isSubmitted() && $formCart->isValid()){
            $cart->setUpdatedAt(new \DateTime());
            $cart->setUser($this->getUser());
            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart_index');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'formCart' => $formCart->createView(),
            'formPromo' => $formPromo->createView()
        ]);
    }

    /**
     * @Route("/clear", name="clear", methods={"POST"})
     */
    public function clear(CartManager $cartManager, EntityManagerInterface $em): Response
    {
        $cart = $cartManager->getCurrentCart();
        
        // Delete the entire cart from database
        $em->remove($cart);
        $em->flush();
        
        $this->addFlash('success', 'Your cart has been cleared.');
        
        return $this->redirectToRoute('app_cart_index');
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
        
        $cartManager->setStatus($cart, Order::STATUS_PENDING);
        
        return $this->render('cart/checkout.html.twig', [
            'order' => $cart
        ]);
    }
}
