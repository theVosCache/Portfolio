<?php

declare(strict_types=1);

namespace App\Auth\Domain\Factory;

use App\Auth\Domain\Entity\Role;

interface RoleFactoryInterface
{
    public function create(string $name): Role;
}
