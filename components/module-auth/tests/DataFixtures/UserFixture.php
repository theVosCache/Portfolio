<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    use PrivatePropertyManipulator;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            name: 'test de Tester',
            email: 'test@test.nl',
            password: 'testtest'
        );

        $user->setPassword(password: $this->hasher->hashPassword(user: $user, plainPassword: 'testtest'));

        $this->setByReflection(object: $user, property: 'id', value: 1);

        $manager->persist($user);
        $manager->flush();
    }
}
