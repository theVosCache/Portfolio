<?php

declare(strict_types=1);

namespace App\Auth\Application\Factory;

use App\Auth\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function create(string $name, string $email, string $password): User
    {
        $user = new User(
            name: $name,
            email: $email,
            password: $password
        );

        $encodedPassword = $this->hasher->hashPassword(user: $user, plainPassword: $password);

        $user = $user->setPassword($encodedPassword);

        return $user;
    }
}
