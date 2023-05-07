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
    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Role::class);
    }

    /** @throws RoleNotFoundException */
    public function findBySlug(string $slug): Role
    {
        $user = $this->findOneBy(criteria: ['slug' => $slug]);

        if (!$user instanceof Role) {
            throw new RoleNotFoundException(message: sprintf(
                "No role found for slug %s",
                $slug
            ));
        }

        return $user;
    }
}
