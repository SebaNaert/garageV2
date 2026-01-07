<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Voiture;
use App\Entity\VoitureImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}
    public function load(ObjectManager $manager): void
    {
        // Crée un admin
        $admin = new User();
        $admin->setEmail('admin@garage.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Admin');
        $admin->setLastName('Garage');

        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'password')
        );

        $manager->persist($admin);
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 12; $i++) {
            $voiture = new Voiture();
            $voiture->setMarque($faker->company);
            $voiture->setModele($faker->word);
            $voiture->setKilometrage($faker->numberBetween(0, 200000));
            $voiture->setPrix($faker->numberBetween(5000, 50000));
            $voiture->setNombreProprietaires($faker->numberBetween(1, 3));
            $voiture->setCylindree($faker->randomElement(['1.0L', '1.2L', '1.6L', '2.0L']));
            $voiture->setPuissance($faker->numberBetween(60, 300));
            $voiture->setCarburant($faker->randomElement(['Essence', 'Diesel', 'Hybride', 'Électrique']));
            $voiture->setAnneeMiseEnCirculation($faker->numberBetween(2000, 2023));
            $voiture->setTransmission($faker->randomElement(['Manuelle', 'Automatique']));
            $voiture->setDescription($faker->paragraph);
            $voiture->setOptions($faker->sentence);

            // Image de couverture
            $coverImage = "https://picsum.photos/id/".(30+$i)."/200/200";
            $voiture->setImageCouverture($coverImage);

            // Galerie : 3 images aléatoires
            for ($j = 0; $j < 3; $j++) {
                $image = new VoitureImage();
                $image->setImageName("https://picsum.photos/id/".(200+$i*3+$j)."/400/250");
                $voiture->addVoitureImage($image);
            }

            $manager->persist($voiture);
        }

        $manager->flush();

    }
}
