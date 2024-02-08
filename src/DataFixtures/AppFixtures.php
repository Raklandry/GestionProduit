<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        //Ajout user et admin
        $user = new User();

        $password = $this->encoder->encodePassword($user, '123456');
        
        $user
            ->setNom('Admin')
            ->setPrenom('admin')
            ->setEmail('admin@gmail.com')
            ->setAdresse('lot 2B')
            ->setTelephone('0331234567')
            ->setPassword($password)
            ->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($user);

        //Ajout fournisseur et produit
        $marques = ["Toyota", "Honda", "Ford", "Chevrolet", "BMW", "Mercedes-Benz", "Audi", "Volkswagen", "Nissan", "Hyundai", "Kia", "Mazda", "Volvo", "Porsche", "Subaru", "Jaguar", "Lexus", "Tesla", "Ferrari", "Maserati"];
        $types = [ "Sedan", "SUV", "Truck", "Hatchback", "Luxury", "Compact", "Sports", "Electric", "Convertible", "Coupe", "Wagon", "Minivan", "Off-road", "Crossover", "Hybrid", "Pickup", "Limousine", "Microcar", "Sedan"];
        for ($i=0; $i < 5; $i++) { 
            $fournisseur = new Fournisseur();
            $fournisseur->setNom($faker->firstName());

            $manager->persist($fournisseur);

            for ($j=0; $j < mt_rand(1, 5); $j++) { 
                $produit = new Produit();

                $produit
                    ->setTitre($faker->jobTitle)
                    ->setDescription($faker->text())
                    ->setQuantiteEnStock(mt_rand(1, 5))
                    ->setMarque($faker->unique()->randomElement($marques))
                    ->setPrixTtc($faker->randomFloat(2, 500, 5000))
                    ->setTypes($faker->randomElement($types))
                    ->setgenre($faker->jobTitle)
                    ->setFournisseur($fournisseur);
                
                $manager->persist($produit);
            }
        }

        $manager->flush();
    }
}
