<?php

namespace App\Form;

use App\Entity\Vacation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VacationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = $options['required'] ? [new NotBlank()] : [];
        
        $builder
            ->add('beginAt', DateTimeType::class, array(
                'required' => $options['required'],
                'label' => 'Début',
                'date_widget' => 'single_text',
                'hours' => array(/* 5,6,7, */8,/* 9,10,11, */12/* ,13, 14,15,16,17,18,19,20,21 */),
                'minutes' => array(0, 30),
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker m-1'
                ],
                'help' => 'Date & heure premier jour de congés',
                'constraints' => $constraints
            ))
            ->add('finishAt', DateTimeType::class, array(
                'required' => $options['required'],
                'label' => 'Reprise',
                'date_widget' => 'single_text',
                'hours' => array(/* 5,6,7, */8,/* 9,10,11,12,13, */14/* ,15,16,17,18,19,20,21 */),
                'minutes' => array(0, 30),
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker m-1'
                ],
                'help' => 'Date & heure de reprise du travail',
                'constraints' => $constraints
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacation::class,
            'validation_groups' => false,
        ]);
    }
}
