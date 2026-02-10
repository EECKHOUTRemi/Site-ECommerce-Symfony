<?php

namespace App\Form\EventListener;

use App\Entity\Order;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemoveCartItemListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'postSubmit'];
    }

    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $cart = $form->getData();

        if (!$cart instanceof Order) {
            return;
        }

        foreach ($form->get('racquets')->all() as $child) {
            if ($child->has('remove') && $child->get('remove')->getData() === true) {
                $racquetOrdered = $child->getData();
                $cart->removeRacquet($racquetOrdered);
            }
        }
    }
}
