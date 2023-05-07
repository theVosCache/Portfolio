<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: User::class);
    }

    /** @throws UserNotFoundException */
    public function findByEmail(string $email): User
    {
        $user = $this->findOneBy(criteria: ['email' => $email]);

        if (!$user instanceof User) {
            throw new UserNotFoundException(message: sprintf(
                "User with email %s is not found",
                $email
            ));
        }

        return $user;
    }
}