<?php

namespace App\Form;

use App\Entity\JourneySheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneySheetType extends AbstractType
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
            ->add('journeygrants', CollectionType::class, array(
                'entry_type' => JourneyGrantType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__journey_prot__',
                'by_reference' => false,
                'attr' => array(
                    'class' => "journey-collection"
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneySheet::class,
            'allow_extra_fields' => true,
        ]);
    }
}
