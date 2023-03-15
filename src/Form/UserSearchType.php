<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\UserSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Nom...'
                ]
            ))
            ->add('firstname', TextType::class, array(
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Prénom...'
                ]
            ))
            ->add('department', EntityType::class, array(
                'class' => Department::class,
                'placeholder' => 'Cat...',
                'choice_label' => 'fullname',
                'multiple' => false,
                'required' => false
            ))
            ->add('isActive', ChoiceType::class, array(
                'required' => false,
                'label' => false,
                'placeholder' => 'Statut...',
                'choices' => array(
                    'actif' => true,
                    'inactif' => false
                ),
                'expanded' => false, 
                'multiple' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
}