<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository implements RoleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Role::class);
    }

    /** @throws RoleNotFoundException */
    public function findBySlug(string $slug): Role
    {
        $user = $this->findOneBy(['slug' => $slug]);

        if (!($user instanceof Role)) {
            throw new RoleNotFoundException(
                message: sprintf(
                    'Role with slug %s not found',
                    $slug
                )
            );
        }

        return $user;
    }

    /** @throws RoleNotFoundException */
    public function findById(int $id): Role
    {
        $user = $this->findOneBy(['id' => $id]);

        if (!($user instanceof Role)) {
            throw new RoleNotFoundException(
                message: sprintf(
                    'Role with id %s not found',
                    $id
                )
            );
        }

        return $user;
    }
}
