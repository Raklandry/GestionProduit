<?php

namespace App\Test;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

class UserRepositoryTest extends KernelTestCase {
    
    public function testCount () {
        self::bootKernel();
        $user = self::$container->get(UserRepository::class)->count([]);
        
        // si le nombre d'user est 2
        $this->assertEquals(2, $user);
    }

    public function getEntity(): User
    {
        return (new User())
            ->setNom('RAN')
            ->setPrenom('Kev')
            ->setEmail('kevran@gmail.com')
            ->setAdresse('lot 2B')
            ->setTelephone('0331234567')
            ->setPassword('azerty');
    }

    public function assertHasErrors(User $user, int $nbError = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);

        $this->assertCount($nbError, $errors);
    }

    public function testEntityIsValid(): void
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testEntityInValid(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail('lensrad'), 1);
    }



}

