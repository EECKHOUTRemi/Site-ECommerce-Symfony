<?php

namespace App\Form;

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
        $builder
            ->add('query', ChoiceType::class, [
                'choices' => $options['weight_choices'],
                'required' => false,
                'placeholder' => 'Select weight',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'weight_choices' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
