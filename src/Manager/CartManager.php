<?php

namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Service\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager{

    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManagerInterface
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->em = $entityManagerInterface;
    }
    
    public function getCurrentCart(): Order{
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart){
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    public function save(Order $cart){
        if(!$cart->getId()){
            $this->em->persist($cart);
        }
        $this->em->flush();
        $this->cartSessionStorage->setCart($cart);
    }
}