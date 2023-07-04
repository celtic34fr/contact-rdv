<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Carbon\Doctrine\DateTimeType;
use Celtic34fr\ContactCore\Enum\EventEnums;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time_at', DateTimeType::class, [
                'required' > true,
            ])
            ->add('objet', TextType::class, [
                'required' => true,
            ])
            ->add('complements', TextareaType::class, [
                'required' => false,
            ])
            ->add('nature', ChoiceType::class, [
                'choices' => array_combine(EventEnums::getValues(), EventEnums::getCases()),
                'choice_label' => 'Nature du rendez-vous',
                "required" => false,
            ])
            ->add('customer_id', HiddenType::class, [
                'required' => false,
            ])
            ->add('contact_id', HiddenType::class, [
                'requires' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalEventForm::class,
        ]);
    }
}
