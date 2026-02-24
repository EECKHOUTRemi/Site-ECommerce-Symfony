<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\PromoCode;
use App\Factory\OrderFactory;
use App\Repository\PromoCodeRepository;
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

    /**
     * @var PromoCodeRepository
     */
    private $promoCodeRepository;

    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManagerInterface,
        PromoCodeRepository $promoCodeRepository
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->em = $entityManagerInterface;
        $this->promoCodeRepository = $promoCodeRepository;
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

    public function remove(){
        $this->em->flush();
        $this->cartSessionStorage->clearCart();

    }

    public function setStatus(Order $cart, $status){
        $cart->setStatus($status);
        $this->em->flush();
        if ($status === Order::STATUS_PENDING){
            $this->cartSessionStorage->clearCart();
        }
    }

    public function handlePromoCode(PromoCode $promo, Order $cart){
        $discount = $promo->getDiscount();
        $currentTotal = $cart->getTotalWithoutDiscount();
        $newTotal = $currentTotal - $currentTotal * ($discount / 100);
        $cart->setPromoCode($promo);
        $cart->setTotal($newTotal);
        $this->save($cart);
    }
}