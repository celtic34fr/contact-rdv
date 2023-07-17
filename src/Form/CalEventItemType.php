<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalEventItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, [
                'required' => false,
            ])
            ->add('cle', TextType::class, [
                'label' => "Clé d'accès",
            ])
            ->add('fonction', TextType::class, [
                'label' => "Descriptif",
            ])
            ->add('border', ColorType::class, [
                'label' => "Couleur de fond",
            ])
            ->add('text', ColorType::class, [
                'label' => "Couleur de bordure",
            ])
            ->add('background', ColorType::class, [
                'label' => "Couleur d'écriture",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => CalEventFE::class,
        ]);
    }
}
