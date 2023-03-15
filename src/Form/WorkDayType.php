<?php

namespace App\Form;

use App\Entity\WorkDay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class WorkDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tasks', CollectionType::class, array(
                'entry_type' => TaskType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__task_prot__',
                'by_reference' => false,
                'attr' => array(
                    'class' => "task-collection"
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkDay::class,
            'allow_extra_fields' => true,
            'constraints' => array(
                new Callback([$this, 'validateOverlapse']),
            )
        ]);
    }

    public function validateOverlapse(WorkDay $day, ExecutionContextInterface $context)
    {       
        $tasks = $day->getTasks();
        
        $agregTasks = [];
        foreach ($tasks as $task) {
            $agregTasks[] = $task;
        }


        for ($i=1; $i < count($agregTasks); $i++) { 
            if ($agregTasks[$i]->getBeginAt() < $agregTasks[$i-1]->getEndAt()) {
                $context->buildViolation('Les interventions '.$i.' et '.($i+1).' du jour '. $day->getDatetime()->format('N').' se chevauchent')
                ->addViolation()
            ;
            }
        }

    }
}
