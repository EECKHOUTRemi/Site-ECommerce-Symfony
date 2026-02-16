<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'choiceInputType',
            ChoiceInputType::class,
                [
                    'mapped'  => false,
                    'block_prefix' => 'choiceInputType',
                    'label'   => false,
                    'choices' => [
                        'test 1' => 1,
                        'test 2' => 2,
                        'test 3' => 3,
                    ]
                ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
