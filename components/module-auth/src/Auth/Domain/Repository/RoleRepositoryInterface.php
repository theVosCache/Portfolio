<?php

declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;

interface RoleRepositoryInterface
{
    /** @return Role[] */
    public function list(): array;

    /** @throws RoleNotFoundException */
    public function findBySlug(string $slug): Role;
}
