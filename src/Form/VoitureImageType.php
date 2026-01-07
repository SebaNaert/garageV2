<?php

namespace App\Form;

use App\Entity\VoitureImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VoitureImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageName', FileType::class, [
                'label' => 'Image',
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg','image/png','image/gif'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
                    ])
                ],
            ])
            ->add('caption', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Titre de l\'image']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VoitureImage::class,
        ]);
    }
}
