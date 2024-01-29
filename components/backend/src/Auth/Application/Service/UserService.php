<?php

declare(strict_types=1);

namespace App\Auth\Application\Service;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserEmailAlreadyExistsException;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private readonly UserRepositoryInterface $userRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    /** @throws UserEmailAlreadyExistsException */
    public function create(string $name, string $email, string $password): User
    {
        try {
            $this->userRepository->findByEmail($email);

            throw new UserEmailAlreadyExistsException("Email already exists");
        } catch (UserNotFoundException $exception) {
            //silent fail
        }

        $user = new User(
            name: $name,
            email: $email,
            password: $password
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        return $user;
    }
}
