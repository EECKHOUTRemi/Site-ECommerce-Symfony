<?php

namespace App\Form\Cart;

use App\Entity\RacquetOrdered;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class)

            ->add('weight', ChoiceType::class, [
                'choices' => [
                    '250g' => 250,
                    '260g' => 260,
                    '270g' => 270,
                    '280g' => 280,
                    '285g' => 285,
                    '290g' => 290,
                    '295g' => 295,
                    '300g' => 300,
                    '305g' => 305,
                    '310g' => 310,
                    '315g' => 315,
                    '320g' => 320,
                    '330g' => 330,
                ],
            ])

            ->add('head_size', ChoiceType::class, [
                'choices' => [
                    '548 cm²' => 548,
                    '581 cm²' => 581,
                    '613 cm²' => 613,
                    '626 cm²' => 626,
                    '632 cm²' => 632,
                    '645 cm²' => 645,
                    '658 cm²' => 658,
                    '671 cm²' => 671,
                    '677 cm²' => 677,
                    '690 cm²' => 690,
                    '710 cm²' => 710,
                    '742 cm²' => 742,
                ],
            ])

            ->add('string_pattern', ChoiceType::class, [
                'choices' => [
                    '16x18' => '16x18',
                    '16x19' => '16x19',
                    '16x20' => '16x20',
                    '18x19' => '18x19',
                    '18x20' => '18x20',
                ],
            ])

            ->add('grip_size', ChoiceType::class, [
                'choices' => [
                    '0 (100mm)' => 0,
                    '1 (103mm)' => 1,
                    '2 (106mm)' => 2,
                    '3 (108mm)' => 3,
                    '4 (110mm)' => 4,
                    '5 (113mm)' => 5,
                ],
            ])
            
            ->add('add', SubmitType::class, [
                'label' => 'Add to cart'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RacquetOrdered::class,
        ]);
    }
}
