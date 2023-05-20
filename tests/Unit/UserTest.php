<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        return (new User())
            ->setFirstName('prénom')
            ->setLastName('nom')
            ->setEmail('email@email.fr')
            ->setPassword('Password123')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        
    }

    public function testEntityValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testInvalidMail()
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        //champs vide
        $user->setEmail('');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
        //champs invalide
        $user->setEmail('email@email');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
        //moins de 2 caractères et format invalide
        $user->setEmail('e');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
        //plus de 180 caractères et format invalide
        $user->setEmail('zefoazfpozifhazpfozhfpzofhfpouzfhpzufhzpfouzfpozufhbzpofuzpfouzfbhpzufbpzfubzpfubzpfuzbhfpuozfhbpzoufbnzpouifbzpfobzpfioubzfpouzbfpuzbafpuzfbpzuoifbzpfubzpfubzfpubzfpuzbfpzoufbzpofubzpfoubzpfubzpfouzbfpzufbzpoufbzpofubzfpo');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
    }
    
    public function testInvalidFirstName()
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        //champs vide
        $user->setFirstName('');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
        //plus de 50 caractères
        $user->setFirstName('eozgnzogbhzpgohgpogbnzogbnzogbzgpozbgzogbzgobzngozbngogbzogbzgozbgozgbzogbzgozb');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
        //moins de 2 caractères
        $user->setFirstName('a');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
    }
    
    public function testInvalidLastName()
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        //champs vide
        $user->setLastName('');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
        //plus de 50 caractères
        $user->setLastName('ikezfoizfhozifhzofhzfozhfzeofhzeofhzeofhzeohezvoizhvozevozvhbzvozbhvozbvzovbzovbz');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
        //moins de 2 caractères
        $user->setLastName('a');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(1, $errors);
    }
    
    public function testInvalidPassword()
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        //champs vide
        $user->setPassword('');
        $errors = $container->get('validator')->validate($user);

        $this->assertCount(1, $errors);
    }


}
