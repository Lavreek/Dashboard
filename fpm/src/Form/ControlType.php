<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'attr' => [
                    'value' => '123',
                ],
            ])
            ->add('today', SubmitType::class, [
                'label' => 'Сегодня',
                'attr' => [
                    'value' => '0'
                ]
            ])
//            ->add('date-start', TextType::class, [
//                'required' => false
//            ])
//            ->add('date-end', TextType::class, [
//                'required' => false
//            ])
//            ->add('today', SubmitType::class, [
//                'attr' => [
//                    'value' => 0
//                ]
//            ])
//            ->add('yesterday', SubmitType::class, [
//                'attr' => [
//                    'value' => -1
//                ]
//            ])
//            ->add('seven-days', SubmitType::class, [
//                'attr' => [
//                    'value' => -7
//                ]
//            ])
//            ->add('thirty-days', SubmitType::class, [
//                'attr' => [
//                    'value' => -30
//                ]
//            ])
//            ->add('this-month', SubmitType::class, [
//                'attr' => [
//                    'value' => 30
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST'
            // Configure your form options here
        ]);
    }
}
