<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientName', TextType::class, array(
                'label' => 'Client',
                'required' => false,
                'empty_data' => '',
                'attr' => array(
                    'class' => 'form-control m-1 col-auto client_name',
                    'autocomplete' => 'off'
                ),
                'constraints' => array(
                    new Length(array(
                        'max' => 100,
                        'maxMessage' => 'Le Lieu d\'intervention doit comporter moins de {{ limit }} caractères.'
                        )
                    )
                )
            ))
            ->add('beginAt', TimeType::class, array(
                'label' => 'Début à :',
                'hours' => array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21),
                'minutes' => array(0, 15, 30, 45),
                'attr' => array(
                    'class' => 'flex-nowrap m-1 col-auto col-md-12 task_begin task_input_time',
                ),
                'label_attr' => array(
                    'class' => 'd-md-none'
                ),
                'constraints' => array(
                    new Callback([$this, 'validateTime'])
                )
            ))
            ->add('endAt', TimeType::class, array(
                'label' => 'Fin à :',
                'hours' => array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21),
                'minutes' => array(0, 15, 30, 45),
                'attr' => array(
                    'class' => 'flex-nowrap m-1 col-auto col-md-12 task_end task_input_time',
                ),
                'label_attr' => array(
                    'class' => 'd-md-none'
                ),
                'constraints' => array(
                    new Callback([$this, 'validateTime']),
                    new LessThanOrEqual( array(
                        'value' => new \DateTime("01/01/1970 T2100"),
                        'message' => 'l\'heure de fin doit être 21h au plus'
                        ))
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'constraints' => array(
                new Callback([$this, 'validateDuration']),
            )
        ]);
    }

    public function validateDuration(Task $task, ExecutionContextInterface $context)
    {
        if ($task->getClientName() && $task->getEndAt() <= $task->getBeginAt()) {
            $context->buildViolation('L\'heure de fin doit être après celle du début.')
                ->atPath('endAt')
                ->addViolation()
            ;
        }
    }

    public function validateTime(\Datetime $data, ExecutionContextInterface $context)
    {
        $minTime = 4;
        $maxTime = 21;
        $time = intval($data->format('H'));

        if ($time < $minTime || $time > $maxTime) {
            $context->buildViolation('Merci de renseigner une heure valide entre '.$minTime.' et '.$maxTime.' heures')
                ->addViolation()
            ;
        }
    }}
