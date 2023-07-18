<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\Role;
use App\Tests\PrivatePropertyManipulator;
use DateTime;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aRoleIsCorrectlyCreated(): void
    {
        $role = new Role(
            name: 'Test Role',
            slug: 'test-role'
        );

        $this->setByReflection(object: $role, property: 'id', value: 1);

        $this->assertInstanceOf(expected: Role::class, actual: $role);
        $this->assertSame(expected: 1, actual: $role->getId());
        $this->assertSame(expected: 'Test Role', actual: $role->getName());
        $this->assertSame(expected: 'test-role', actual: $role->getSlug());
        $this->assertInstanceOf(expected: DateTime::class, actual: $role->getCreatedAt());
        $this->assertInstanceOf(expected: DateTime::class, actual: $role->getUpdatedAt());
    }

    /** @test */
    public function aRoleCanBeUpdated(): void
    {
        $role = new Role(
            name: 'Test Role',
            slug: 'test-role'
        );
        $this->setByReflection(object: $role, property: 'updatedAt', value: new DateTime('2023-01-01 00:00:00'));

        $this->assertInstanceOf(expected: Role::class, actual: $role);

        $role->setName(name: 'New Role')
            ->setSlug(slug: 'new-role');

        $this->assertSame(expected: 'New Role', actual: $role->getName());
        $this->assertSame(expected: 'new-role', actual: $role->getSlug());
        $this->assertNotSame(expected: '2023-01-01 00:00:00', actual: $role->getUpdatedAt()->format(format: 'Y-m-d H:i:s'));
    }
}
