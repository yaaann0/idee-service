<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Nouveau  mot de passe',
                        ]),
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ])/* ,
                        new Regex([
                            'message' => 'Votre mot de passe doit respecter les contraintes',
                            'pattern' => '/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])).{8,}/'
                        ]) */
                    ],
                    'label' => 'Nouveau mot de passe',
                    'help' => 'Il doit contenir au moins 5 caractères.'
                ],
                'second_options' => [
                    'label' => 'Confirmer mot de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
