<?php 

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

Class UsersFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $user = (New User())
            ->setEmail("admin@domain.fr")
            ->setPassword("Admin000")
            ->setFirstName("Admin")
            ->setLastName("Admin")
            ->setRoles(['ROLE_ADMIN','ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist(($user));
        for ($i=0; $i < 10; $i++) { 
            $user = (New User())
            ->setEmail($faker->email)
            ->setPassword("0000")
            ->setFirstName("$faker->firstName")
            ->setLastName("$faker->lastName")
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist(($user));
        }
        $manager->flush();
    }
}