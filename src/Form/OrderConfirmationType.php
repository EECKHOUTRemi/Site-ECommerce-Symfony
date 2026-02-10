<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('confirm', SubmitType::class, [
                'label' => $options['confirm_label'],
                'attr' => [
                    'class' => $options['confirm_button_class']
                ]
            ])
            ->add('cancel', SubmitType::class, [
                'label' => $options['cancel_label'],
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'confirm_label' => 'Confirm Order',
            'cancel_label' => 'Cancel Order',
            'confirm_button_class' => 'btn btn-success',
        ]);

        $resolver->setAllowedTypes('confirm_label', 'string');
        $resolver->setAllowedTypes('cancel_label', 'string');
        $resolver->setAllowedTypes('confirm_button_class', 'string');
    }
}
