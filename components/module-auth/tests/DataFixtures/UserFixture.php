<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

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
            name: 'test de Tester',
            email: 'test@test.nl',
            password: 'testtest'
        );

        $this->setByReflection(object: $user, property: 'id', value: 1);

        $manager->persist($user);
        $manager->flush();
    }
}
