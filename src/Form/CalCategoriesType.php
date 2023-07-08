<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Celtic34fr\ContactRendezVous\Form\CalCategoryType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoriesFE;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CalCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('values', CollectionType::class, [
                'label' => 'Liste des catégories',
                'entry_type' => CalCategoryType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalCategoriesFE::class,
        ]);
    }
}
