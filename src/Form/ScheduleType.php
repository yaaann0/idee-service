<?php

namespace App\Form;

use App\Entity\Weeksheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workDays', CollectionType::class, array(
                'entry_type' => WorkDayType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__day_prot__',
                'attr' => array(
                    'class' => 'parent-collection'
                )
            ))
            ->add('comment', TextType::class, array(
                'required' => false,
                'label' => 'Si vous n\'avez pas d\'heures cette semaine : ',
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder' => 'Congés, absence...',
                    'maxlength'=> '30'
                ),
                'help' => 'Préciser un motif, 30 carac. maxi',
                'constraints' => array(
                    new Length(array(
                        'max' => 30,
                        'maxMessage' => 'Le commentaire ne doit pas contenir plus de {{ limit }} caractères.'
                        )
                    ),
                )
            ))
            ->add('admin_comment', TextareaType::class, array(
                'required' => false,
                'label' => 'Commentaire : ',
                'attr' => array(
                    'rows' => '2'
                ),
                'help' => '250 carac. maxi',
                'constraints' => array(
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'Le commentaire ne doit pas contenir plus de {{ limit }} caractères.'
                        )
                    ),
                )
            ))
            ->add('unvalidate', SubmitType::class, array(
                'label' => 'Rendre modifiable par le salarié',
                'attr' => array(
                    'class' => 'btn btn-info',
                    'id' => 'unvalidate',
                    'value' => 'unvalidate'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'id' => 'save',
                    'value' => 'save'
                )
            ))
            ->add('validate', SubmitType::class, array(
                'label' => 'Valider',
                'attr' => array(
                    'class' => 'btn btn-success px-4',
                    'id' => 'validate',
                    'value' => 'validate'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Weeksheet::class,
            'attr' => array(
                'class' => 'mt-4 mb-3',
                'id' => 'schedules_form'
            )
        ]);
    }
}
