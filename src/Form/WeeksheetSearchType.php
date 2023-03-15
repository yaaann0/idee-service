<?php

namespace App\Form;

use App\Entity\WeeksheetSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class WeeksheetSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('lastname', TextType::class, array(
            'required' => false,
            'constraints' => array(
                new Length(array(
                    'max' => 30,
                    'maxMessage' => 'Le nom doit comporter moins de {{ limit }} caractères.'
                    )
                )
            ),
            'label' => 'Salarié :',
            'attr' => [
                'placeHolder' => 'Nom...',
            ],
        ))
        ->add('from', DateType::class, array(
            'required' => false,
            'label' => 'Entre :',
            'widget' => 'single_text',
            'html5' => true,
            'attr' => ['class' => 'js-datepicker'],
        ))
        ->add('to', DateType::class, array(
            'required' => false,
            'label' => 'Et :',
            'widget' => 'single_text',
            'html5' => true,
            'attr' => ['class' => 'js-datepicker'],
        ))
        ->add('state', ChoiceType::class, array(
            'required' => false,
            'label' => 'Statut :',
            'choices' => array(
                '----' => null,
                'A saisir' => 'draft',
                'Validée' => 'validated',
                'Signée' => 'signed'
            ),
            'expanded' => false, 
            'multiple' => false,
        ))
        ->add('isUpdated', ChoiceType::class, array(
            'required' => false,
            'label' => 'Modifiée :',
            'choices' => array(
                '----' => null,
                'oui' => true,
                'non' => false
            ),
            'expanded' => false, 
            'multiple' => false,
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WeeksheetSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'attr' => ['id' =>'search'],
            'constraints' => array(
                new Callback([$this, 'validateInterval']),
            )
        ]);
    }

    public function validateInterval(WeeksheetSearch $search, ExecutionContextInterface $context)
    {
        if ($search->getFrom() && $search->getTo()) {
            if ($search->getFrom() > $search->getTo()) {
                $context->buildViolation('Merci de renseigner un interval valide')
                ->atPath('to')
                ->addViolation()
            ;
            }
        }
    }
}
