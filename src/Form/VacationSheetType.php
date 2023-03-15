<?php

namespace App\Form;

use App\Entity\VacationSheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacationSheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vacations', CollectionType::class, array(
                'entry_type' => VacationChoicesType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__vac_prot__',
                'by_reference' => false,
                'attr' => array(
                    'class' => "vacation-collection"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VacationSheet::class,
            'allow_extra_fields' => true,
        ]);
    }
}
