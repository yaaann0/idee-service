<?php

namespace App\Form;

use App\Entity\VacationChoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class VacationChoicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason',TextType::class, array(
                'label' => 'Motif',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control m-1 col-auto',
                    'placeholder'=> 'Motif de l\'absence'
                ),
                'help' => 'congés payés, RTT, etc',
                'constraints' => array(
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Ce champ doit comporter moins de {{ limit }} caractères.'
                        )
                    ),
                    new NotBlank()
                )
            ))
            ->add('first', VacationType::class, array(
                'required' => true,
            ))
            ->add('second', VacationType::class, array(
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VacationChoices::class,
        ]);
    }
}
