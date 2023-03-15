<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true,
                'label' => 'Titre :'
            ))
            ->add('newsFile', FileType::class, array(
                'label' => 'Pièce jointe :',
                'required' => false,
                'help' => '.pdf .doc .docx .odt .png .jpeg < 2 Mo',
                'constraints' => array(
                    new File(array(
                        'maxSize' => "2M",
                        'mimeTypes' => array(
                            "application/pdf",
                            "application/x-pdf",
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg',
                            'image/png'
                        ),
                        'mimeTypesMessage' => 'Merci de joindre un fichier au format valide'
                    ))
                ),
                'attr' => array(
                    'placeholder' => 'Choisissez un fichier',
                    'accept' => "application/pdf, application/x-pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
