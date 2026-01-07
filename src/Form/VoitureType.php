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
use Symfony\Component\Validator\Constraints as Assert;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La marque est obligatoire']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La marque doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'La marque ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('modele', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le modèle est obligatoire']),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' => 'Le modèle doit faire au moins {{ limit }} caractère',
                        'maxMessage' => 'Le modèle ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('kilometrage', IntegerType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Le kilométrage est obligatoire']),
                    new Assert\PositiveOrZero(['message' => 'Le kilométrage doit être positif ou nul']),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\NotNull(['message' => 'Le prix est obligatoire']),
                    new Assert\Positive(['message' => 'Le prix doit être positif']),
                ],
            ])
            ->add('nombreProprietaires', IntegerType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Le nombre de propriétaires est obligatoire']),
                    new Assert\PositiveOrZero(['message' => 'Le nombre de propriétaires doit être positif ou nul']),
                ],
            ])
            ->add('cylindree', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La cylindrée est obligatoire']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'La cylindrée ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('puissance', IntegerType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'La puissance est obligatoire']),
                    new Assert\Positive(['message' => 'La puissance doit être positive']),
                ],
            ])
            ->add('carburant', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le carburant est obligatoire']),
                    new Assert\Choice([
                        'choices' => ["Essence", "Diesel", "Hybride", "Électrique"],
                        'message' => 'Le carburant doit être Essence, Diesel, Hybride ou Électrique',
                    ]),
                ],
            ])
            ->add('anneeMiseEnCirculation', IntegerType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'L\'année est obligatoire']),
                    new Assert\Range([
                        'min' => 1900,
                        'max' => 2100,
                        'notInRangeMessage' => 'L\'année doit être comprise entre {{ min }} et {{ max }}',
                    ]),
                ],
            ])
            ->add('transmission', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La transmission est obligatoire']),
                    new Assert\Choice([
                        'choices' => ["Manuelle", "Automatique"],
                        'message' => 'La transmission doit être Manuelle ou Automatique',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'La description doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('options', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 500,
                        'maxMessage' => 'Les options ne peuvent pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('imageCouverture', FileType::class, [
                'label' => 'Image de couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
                    ]),
                ],
            ])
            ->add('voitureImages', CollectionType::class, [
                'entry_type' => VoitureImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
