<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItems;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CalEventItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => 'Liste des catégories',
                'entry_type' => CalEventItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalEventItems::class,
        ]);
    }
}
