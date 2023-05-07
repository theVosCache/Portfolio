<?php

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

        $role = $roleFactory->create("Test Role");

        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame("Test Role", $role->getName());
        $this->assertSame("test-role", $role->getSlug());
    }
}