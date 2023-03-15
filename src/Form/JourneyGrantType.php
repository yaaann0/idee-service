<?php

namespace App\Form;

use App\Entity\JourneyGrant;
use App\Entity\Sector;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class JourneyGrantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', DateType::class, array(
                'required' => true,
                'label' => 'Date',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'js-datepicker '
                ],
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('manager',TextType::class, array(
                'label' => 'Responsable',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control  col-auto',
                ),
                'constraints' => array(
                    new Length(array(
                        'max' => 100,
                        'maxMessage' => 'Ce champ doit comporter moins de {{ limit }} caractères.'
                        )
                    ),
                    /* new NotBlank() */
                )
            ))
            ->add('client',TextType::class, array(
                'label' => 'Client',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control  col-auto client_name',
                    'autocomplete' => 'off'
                ),
                'constraints' => array(
                    new Length(array(
                        'max' => 100,
                        'maxMessage' => 'Ce champ doit comporter moins de {{ limit }} caractères.'
                        )
                    ),
                    new NotBlank()
                )
            ))
            ->add('city',TextType::class, array(
                'label' => 'Commune du chantier',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control  col-auto',
                ),
                'constraints' => array(
                    new Length(array(
                        'max' => 100,
                        'maxMessage' => 'Ce champ doit comporter moins de {{ limit }} caractères.'
                        )
                    ),
                    new NotBlank()
                )
            ))
            ->add('distance', IntegerType::class, array(
                'label' => 'Distance du siège',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control  col-auto',
                ),
                'constraints' => array(
                    new Positive(),
                    new NotBlank()
                )
            ))
            ->add('sector', EntityType::class, array(
                'class' => Sector::class,
                'choice_label' => 'fullname',
                'label' => 'Secteur',
                'multiple' => false,
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'class' => ''
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneyGrant::class,
        ]);
    }
}
