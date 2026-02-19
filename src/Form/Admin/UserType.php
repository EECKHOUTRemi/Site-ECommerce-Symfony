<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\Search\ChoiceInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles', ChoiceInputType::class, [
                'choices' => $options['roles'],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
            ])
            ->add('password')
            ->add('lastname')
            ->add('firstname')
            ->add('phone', TelType::class)
            ->add('email', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles' => [],
        ]);
    }
}
