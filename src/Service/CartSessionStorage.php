<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var OrderRepository
     */
    private $cartRepository;

    /**
     * @var string
     */
    const CART_KEY_NAME = 'cart_id';

    public function __construct(RequestStack $requestStack, OrderRepository $cartRepository)
    {
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
    }

    public function setCart(Order $cart){
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    public function getCart() : ?Order
    {
        return $this->cartRepository->findOneBy([
            'id' => $this->getCartId(),
            'status' => Order::STATUS_CART
        ]);
    }

    public function clearCart(){
        $this->getSession()->clear();
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}