<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', TextType::class)
            ->add('modele', TextType::class)
            ->add('kilometrage', IntegerType::class)
            ->add('prix', MoneyType::class, ['currency' => 'EUR'])
            ->add('nombreProprietaires', IntegerType::class)
            ->add('cylindree', TextType::class)
            ->add('puissance', IntegerType::class)
            ->add('carburant', TextType::class)
            ->add('anneeMiseEnCirculation', IntegerType::class)
            ->add('transmission', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('options', TextareaType::class, ['required' => false])
            ->add('imageCouverture', FileType::class, [
                'label' => 'Image de couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg','image/png','image/gif'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
                    ])
                ],
            ])
            ->add('voitureImages', CollectionType::class, [
                'entry_type' => VoitureImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
