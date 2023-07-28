<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Message;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: User::class);
    }

    /** @throws UserNotFoundException */
    public function findById(int $id): User
    {
        $user = $this->findOneBy(['id' => $id]);

        if (!($user instanceof User)) {
            throw new UserNotFoundException(
                message: sprintf(
                    'User with id %s not found',
                    $id
                )
            );
        }

        return $user;
    }

    /** @throws UserNotFoundException */
    public function findByEmail(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (!($user instanceof User)) {
            throw new UserNotFoundException(
                message: sprintf(
                    'User for email %s not found',
                    $email
                )
            );
        }

        return $user;
    }
}
