<?php

namespace App\Form;

use App\Model\FilterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weight', ChoiceType::class, [
                'choices' => $options['weight_choices'],
                'required' => false,
                'placeholder' => 'Select weight',
            ])
            ->add('head_size', ChoiceType::class, [
                'choices' => $options['head_size_choices'],
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
            ->add('reset', ResetType::class, [
                'label' => 'Clear filters',
                'attr' => ['class' => 'btn btn-outline-light btn-sm flex-fill']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'weight_choices' => [],
            'head_size_choices' => [],
            'string_pattern_choices' => [],
            'grip_size_choices' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
