<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celtic34fr\ContactRendezVous\FormEntity\InputEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class InputEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => "Date de début",
            ])
            ->add('end', DateTimeType::class, [
                'label' => "Date de fin",
            ])
            ->add('typeEvt', TextType::class)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('allDay', CheckboxType::class, [
                'label' => 'Toute la journée',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InputEvent::class,
        ]);
    }

}
