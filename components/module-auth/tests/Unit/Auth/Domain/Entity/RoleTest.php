<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\Role;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aRoleCanBeCreated(): void
    {
        $role = new Role(
            name: "Test Role",
            slug: "test-role"
        );

        $this->setByReflection($role, 'id', 1);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame(1, $role->getId());
        $this->assertSame("Test Role", $role->getName());
        $this->assertSame('test-role', $role->getSlug());
    }

    /** @test */
    public function aRoleCanBeUpdated(): void
    {
        $role = new Role(
            name: "Test Role",
            slug: "test-role"
        );

        $this->setByReflection($role, 'id', 1);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame(1, $role->getId());
        $this->assertSame("Test Role", $role->getName());
        $this->assertSame('test-role', $role->getSlug());

        $role->setName('New Role')->setSlug('new-role');

        $this->assertSame("New Role", $role->getName());
        $this->assertSame('new-role', $role->getSlug());
    }
}
