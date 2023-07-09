<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoryFE;

class CalCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Clé d'accès",
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Length([
                        'min' => 4, 'minMessage' => "La clé doit faire au moins 4 caractères",
                        'max' => 16, "maxMessage" => "La clé doit faire au plus 16 caractères"
                    ])
                ],
            ])
            ->add('description', TextType::class, [
                'label' => "Description",
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Length([
                        'min' => 4, 'minMessage' => "La description doit faire au moins 4 caractères",
                        'max' => 255, "maxMessage" => "La description doit faire au plus 255 caractères"
                    ])
                ],
                ])
            ->add('background_color', ColorType::class, [
                'label' => 'Couleur fond de case',
                'html5' => true,
                'empty_data' => "#FFFFFF",
                ])
            ->add('border_color', ColorType::class, [
                'label' => 'Couleur bordure de case',
                'html5' => true,
                'empty_data' => "#FFFFFF",
                ])
            ->add('text_color', ColorType::class, [
                'label' => 'Couleur écriture',
                'html5' => true,
                'empty_data' => "#000000",
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalCategoryFE::class,
        ]);
    }
}
