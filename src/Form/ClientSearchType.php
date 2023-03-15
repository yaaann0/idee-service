<?php

namespace App\Form;

use App\Entity\ClientSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, array(
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Nom...'
                ]
            ))
            ->add('city', TextType::class, array(
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Ville...'
                ]
            ))
            ->add('postalCode', IntegerType::class, array(
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Code postal...'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
}
