<?php

namespace App\Form;

use App\Entity\MealSheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealSheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month', ChoiceType::class, array(
                'required' => true,
                'label' => 'Mois',
                'placeholder' => 'Mois',
                'choices' => [
                    'Janvier' => 'Janvier', 
                    'Février' => 'Février', 
                    'Mars' => 'Mars', 
                    'Avril' => 'Avril', 
                    'Mai' => 'Mai', 
                    'Juin' => 'Juin', 
                    'Juillet' => 'Juillet', 
                    'Août' => 'Août', 
                    'Septembre' => 'Septembre', 
                    'Octobre' => 'Octobre', 
                    'Novembre' => 'Novembre', 
                    'Décembre' => 'Décembre'
                    ]
            ))
            ->add('year', IntegerType::class, array(
                'required' => true,
                'label' => 'Année',
            ))
            ->add('mealgrants', CollectionType::class, array(
                'entry_type' => MealGrantType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__meal_prot__',
                'by_reference' => false,
                'attr' => array(
                    'class' => "meal-collection"
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MealSheet::class,
            'allow_extra_fields' => true,
        ]);
    }
}
