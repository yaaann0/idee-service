<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Mot de passe',
                    'help' => 'Il doit contenir au moins 5 caractères.'
                ),
                'second_options' => array(
                    'label' => 'Confirmer mot de passe',
                ),
                'invalid_message' => 'Les mots de passe doivent être identiques',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
