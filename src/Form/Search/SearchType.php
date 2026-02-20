<?php

namespace App\Form\Search;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search',
                ]
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

            ->add('submit', SubmitType::class, [
                'label' => 'Apply',
                'attr' => ['class' => 'btn btn-light btn-sm flex-fill']
            ])
        ;;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'string_pattern_choices' => [],
            'grip_size_choices' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
