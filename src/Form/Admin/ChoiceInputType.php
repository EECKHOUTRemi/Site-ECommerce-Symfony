<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceInputType
 */
class ChoiceInputType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'choiceType',
                ChoiceType::class,
                [
                    'required' => false,
                    'choices'  => $options['choices'],
                    'multiple' => $options['multiple'],
                    'expanded' => $options['expanded'],
                ]
            )
            ->add(
                'choiceInput',
                TextType::class,
                [
                    'required' => false,
                    'label'    => false,
                    'attr'     => [
                        'placeholder' => 'Enter new role',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['choices'] = $options['choices'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['choices']);

        $resolver->setDefaults(
            [
                'choices' => [],
                'multiple' => false,
                'expanded' => false
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'choiceInputType';
    }
}
