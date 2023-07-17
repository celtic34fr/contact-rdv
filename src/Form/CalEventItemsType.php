<?php

namespace Celtic34fr\ContactRendezVous\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalEventItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('values', CollectionType::class, [
                'label' => 'Liste des catégories',
                'entry_type' => CalEventItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('record', SubmitType::class, [
                'label' => 'Enregitrer',
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
