<?php

namespace App\Factory;

use DateTime;
use App\Entity\Order;
use App\Entity\Racquet;
use App\Entity\RacquetOrdered;

class OrderFactory{

    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
            
        return $order;
    }

    public function createRacquet(Racquet $racquet): RacquetOrdered
    {
        $racquetOrdered = new RacquetOrdered();
        $racquetOrdered->setRacquet($racquet);
        $racquetOrdered->setQuantity(1);

        return $racquetOrdered;
    }
}