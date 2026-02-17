<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RacquetRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 10,
                        'notInRangeMessage' => 'Rating must be between {{ min }} and {{ max }}.',
                    ])
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
