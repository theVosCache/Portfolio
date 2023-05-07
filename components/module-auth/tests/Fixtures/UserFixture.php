<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    use PrivatePropertyManipulator;

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );

        $this->setByReflection(object: $user, property: 'id', value: 1);

        $password = $this->userPasswordHasher->hashPassword(user: $user, plainPassword: 'test');
        $user->setPassword(password: $password);

        $manager->persist($user);
        $manager->flush();
    }
}