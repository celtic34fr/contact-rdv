<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Celtic34fr\ContactCore\Enum\EventEnums;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CalEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $natureChoices = EventEnums::getValuesCases;

        $builder
            ->add('time_at', DateTimeType::class, [
                'date_label' => 'le ', 'input' => 'string', 'input_format' => "d/m/Y H:s:i",
                'required' => false,
            ])
            ->add('objet', TextType::class, [
                'required' => true,
            ])
            ->add('complements', TextareaType::class, [
                'required' => false,
            ])
            ->add('nature', ChoiceType::class, [
                'choices' => $natureChoices,
                'choice_label' => 'Nature du rendez-vous',
                "required" => false,
            ])
            ->add('customer_id', HiddenType::class, [
                'required' => false,
            ])
            ->add('contact_id', HiddenType::class, [
                'required' => false,
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
