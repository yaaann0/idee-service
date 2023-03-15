<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array( 'label' => 'Email :'))
            ->add('lastname', TextType::class, array( 'label' => 'Nom :'))
            ->add('firstname', TextType::class, array( 'label' => 'Prénom :'))
            ->add('department', EntityType::class, array(
                'class' => Department::class,
                'placeholder' => 'Catégorie...',
                'choice_label' => 'fullname',
                'label' => 'Catégorie : ',
                'multiple' => false
            ))
            ->add('isActive', ChoiceType::class, array(
                'required' => true,
                'label' => 'Statut',
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
            'data_class' => User::class,
        ]);
    }
}
