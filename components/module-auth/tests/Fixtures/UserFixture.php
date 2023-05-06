<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    use PrivatePropertyManipulator;

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );

        $this->setByReflection($user, 'id', 1);
        $user->setPassword('password');

        $manager->persist($user);
        $manager->flush();
    }
}
