<?php

declare(strict_types=1);

namespace App\Auth\Application\Factory;

use App\Auth\Application\Service\SlugService;
use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Factory\RoleFactoryInterface;

class RoleFactory implements RoleFactoryInterface
{
    public function __construct(
        private readonly SlugService $slugService
    ) {
    }

    public function create(string $name): Role
    {
        return new Role(name: $name, slug: $this->slugService->create($name));
    }
}
