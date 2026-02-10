<?php 

namespace App\Form\EventListener;

use App\Entity\Order;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClearCartListener implements EventSubscriberInterface{

    public static function getSubscribedEvents(): array{
        return [FormEvents::POST_SUBMIT => 'postSubmit'];
    }

    public function postSubmit(FormEvent $event): void {
        $form = $event->getForm();
        $cart = $form->getData();

        if (!$cart instanceof Order){
            return;
        }

        /** @var SubmitType $clearButton  */
        $clearButton = $form->get('clear');
        if (!$clearButton->isClicked()) {
            return;
        }

        $cart->removeRacquets();
    }
}