<?php 

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
/**
 * @codeCoverageIgnore
 */
Class UsersFixtures extends Fixture 
{
    public const USER_REFERENCE = 'user-julien';
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
        )
    {}
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $user = (New User())
            ->setEmail("admin@domain.fr")
            ->setFirstName("Admin")
            ->setLastName("Admin")
            ->setRoles(['ROLE_ADMIN']);
        $user->setPassword( $this->userPasswordHasher->hashPassword(
                $user,
                'Admin123'
        ));
            $manager->persist(($user));
        for ($i=0; $i < 10; $i++) { 
            $user = (New User())
            ->setEmail($faker->email())
            ->setFirstName("$faker->firstName")
            ->setLastName("$faker->lastName");
            $user->setPassword( $this->userPasswordHasher->hashPassword(
                $user,
                '123456789'
        ));
            $manager->persist(($user));
            $this->addReference('user-'.$i, $user);
            // $this->counter++;
        }
        $manager->flush();
    }
}