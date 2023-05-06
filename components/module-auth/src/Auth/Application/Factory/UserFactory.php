<?php

declare(strict_types=1);

namespace App\Auth\Application\Factory;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Factory\UserFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function create(string $firstName, string $lastName, string $email, string $password): User
    {
        $user = new User(firstName: $firstName, lastName: $lastName, email: $email);

        $user->setPassword($this->userPasswordHasher->hashPassword(user: $user, plainPassword: $password));

        return $user;
    }
}
