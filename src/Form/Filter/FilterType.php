<?php

namespace App\Form\Filter;

use App\Model\FilterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('brand', ChoiceType::class, [
                'choices' => $options['brand_choices'],
                'required' => false,
                'placeholder' => 'Select brand',
            ])

            ->add('quantity', ChoiceType::class, [
                'choices' => [
                    '< 10' => 1,
                    '10 - 30' => 2,
                    '30 - 60' => 3,
                    '> 60' => 4,
                ],
                'required' => false,
                'placeholder' => 'Select stock quantity',
            ])
            
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '0 - 2' => 1,
                    '3 - 6' => 2,
                    '7 - 10' => 3
                ],
                'required' => false,
                'placeholder' => 'Select rating',
            ])

            ->add('price', ChoiceType::class, [
                'choices' => [
                    '< 100' => 1,
                    '100 - 200' => 2,
                    '200 - 300' => 3,
                    '> 300' => 4
                ],
                'required' => false,
                'placeholder' => 'Select price',
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Apply',
                'attr' => ['class' => 'btn btn-light btn-sm flex-fill']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'brand_choices' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
