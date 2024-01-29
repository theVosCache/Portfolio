<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MariaDbUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /** @throws UserNotFoundException */
    public function findByEmail(string $email): ?User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (empty($user)) {
            throw new UserNotFoundException(
                sprintf("User with Email: %s is not found", $email)
            );
        }

        return $user;
    }

    /** @throws UserNotFoundException */
    public function findByUuid(string $uuid): ?User
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (empty($user)) {
            throw new UserNotFoundException("User not found");
        }

        return $user;
    }
}
