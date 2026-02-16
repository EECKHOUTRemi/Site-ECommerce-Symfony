<?php

namespace App\Form;

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

            ->add('weight', ChoiceType::class, [
                'choices' => [
                    '< 270g' => 1,
                    '270 - 290g' => 2,
                    '290 - 310g' => 3,
                    ' > 310g' => 4
                ],
                'required' => false,
                'placeholder' => 'Select weight',
            ])

            ->add('head_size', ChoiceType::class, [
                'choices' => [
                    '< 630 cm²' => 1,
                    '630 - 660 cm²' => 2,
                    '660 - 690 cm²' => 3,
                    ' > 690 cm²' => 4
                ],
                'required' => false,
                'placeholder' => 'Select head size',
            ])

            ->add('string_pattern', ChoiceType::class, [
                'choices' => $options['string_pattern_choices'],
                'required' => false,
                'placeholder' => 'Select string pattern',
            ])

            ->add('grip_size', ChoiceType::class, [
                'choices' => $options['grip_size_choices'],
                'required' => false,
                'placeholder' => 'Select grip size',
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
