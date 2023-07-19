<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Celtic34fr\ContactRendezVous\FormEntity\InputEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class InputEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startBG', DateType::class, [
                'label' => "Date de début",
            ])
            ->add('hourBG', TimeType::class, [
                'label' => "horaire de début",
            ])
            ->add('startED', DateType::class, [
                'label' => "Date de fin",
            ])
            ->add('hourED', TimeType::class, [
                'label' => "horaire de fin",
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
