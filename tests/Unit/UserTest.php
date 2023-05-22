<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getUser(): User
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
    public function assertHasErrors(User $user, int $number = 0 )
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error){
            $messages[] = $error->getPropertyPath().' -> '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testEntityUserValid(): void
    {
        $this->assertHasErrors($this->getUser(), 0);
    }
    
    public function testBlankMail()
    {
        $this->assertHasErrors($this->getUser()->setEmail(''), 2);
    }

    public function testInvalidMail()
    {
        $this->assertHasErrors($this->getUser()->setEmail('email@email'), 1);
    }
    
    public function testMinLengthMail()
    {
        $this->assertHasErrors($this->getUser()->setEmail('e'), 2);
    }
    
    public function testMaxLengthMail()
    {
        $this->assertHasErrors($this->getUser()->setEmail('zefoazfpozifhazpfozhfpzofhfpouzfhpzufhzpfouzfpozufhbzpofuzpfouzfbhpzufbpzfubzpfubzpfuzbhfpuozfhbpzoufbnzpouifbzpfobzpfioubzfpouzbfpuzbafpuzfbpzuoifbzpfubzpfubzfpubzfpuzbfpzoufbzpofubzpfoubzpfubzpfouzbfpzufbzpoufbzpofubzfpo'), 2);
    }
    
    
    public function testBlankFirstName()
    {
        $this->assertHasErrors($this->getUser()->setFirstName(''), 2);
    }
    
    public function testMinLengthFirstName()
    {
        $this->assertHasErrors($this->getUser()->setFirstName('a'), 1);
    }

    public function testMaxLengthFirstName()
    {
        $this->assertHasErrors($this->getUser()->setFirstName('eozgnzogbhzpgohgpogbnzogbnzogbzgpozbgzogbzgobzngozbngogbzogbzgozbgozgbzogbzgozb'), 1);
    }

    public function testBlankLastName()
    {
        $this->assertHasErrors($this->getUser()->setLastName(''), 2);
    }
    
    public function testMinLengthLastName()
    {
        $this->assertHasErrors($this->getUser()->setLastName('a'), 1);
    }

    public function testMaxLengthLastName()
    {
        $this->assertHasErrors($this->getUser()->setLastName('eozgnzogbhzpgohgpogbnzogbnzogbzgpozbgzogbzgobzngozbngogbzogbzgozbgozgbzogbzgozb'), 1);
    }
    
    
    public function testBlankPassword()
    {
        $this->assertHasErrors($this->getUser()->setPassword(''), 1);
    }

}