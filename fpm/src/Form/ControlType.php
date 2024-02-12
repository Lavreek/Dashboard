<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ])
            ->add('date-start', DateType::class, [
                'required' => false,
                'attr' => [
                    'data-action' => 'change->control#selectDate',
                    'value' => date('Y-m-d', strtotime('-7 days'))
                ]
            ])
            ->add('date-end', DateType::class, [
                'required' => false,
                'attr' => [
                    'data-action' => 'change->control#selectDate',
                    'value' => date('Y-m-d', strtotime('+1 days'))
                ]
            ])
            ->add('today', ButtonType::class, [
                'label' => 'Сегодня',
                'attr' => [
                    'data-action' => 'click->control#selectProgram',
                    'value' => 0
                ]
            ])
            ->add('yesterday', SubmitType::class, [
                'label' => 'Вчера',
                'attr' => [
                    'data-action' => 'click->control#selectProgram',
                    'value' => -1
                ]
            ])
            ->add('seven-days', SubmitType::class, [
                'label' => 'За 7 дней',
                'attr' => [
                    'data-action' => 'click->control#selectProgram',
                    'value' => -7
                ]
            ])
            ->add('thirty-days', SubmitType::class, [
                'label' => 'За 30 дней',
                'attr' => [
                    'data-action' => 'click->control#selectProgram',
                    'value' => -30
                ]
            ])
            ->add('this-month', SubmitType::class, [
                'attr' => [
                    'data-action' => 'click->control#selectProgram',
                    'value' => 30
                ]
            ])
            ->add('program', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'data-control-target' => 'form',
            ],
            'method' => 'POST'
            // Configure your form options here
        ]);
    }
}
