<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\Factory;

use App\Auth\Application\Factory\RoleFactory;
use App\Auth\Application\Service\SlugService;
use App\Auth\Domain\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleFactoryTest extends TestCase
{
    /** @test */
    public function aRoleCanBeCreated(): void
    {
        $roleFactory = new RoleFactory(
            slugService: new SlugService()
        );

        $role = $roleFactory->create(name: "Test Role");

        $this->assertInstanceOf(expected: Role::class, actual: $role);
        $this->assertSame(expected: "Test Role", actual: $role->getName());
        $this->assertSame(expected: "test-role", actual: $role->getSlug());
    }
}
