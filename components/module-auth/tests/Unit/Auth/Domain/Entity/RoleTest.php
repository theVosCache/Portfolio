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

        $this->setByReflection(object: $role, property: 'id', value: 1);

        $this->assertInstanceOf(expected: Role::class, actual: $role);
        $this->assertSame(expected: 1, actual: $role->getId());
        $this->assertSame(expected: "Test Role", actual: $role->getName());
        $this->assertSame(expected: 'test-role', actual: $role->getSlug());
    }

    /** @test */
    public function aRoleCanBeUpdated(): void
    {
        $role = new Role(
            name: "Test Role",
            slug: "test-role"
        );

        $this->setByReflection(object: $role, property: 'id', value: 1);

        $this->assertInstanceOf(expected: Role::class, actual: $role);
        $this->assertSame(expected: 1, actual: $role->getId());
        $this->assertSame(expected: "Test Role", actual: $role->getName());
        $this->assertSame(expected: 'test-role', actual: $role->getSlug());

        $role->setName(name: 'New Role')->setSlug(slug: 'new-role');

        $this->assertSame(expected: "New Role", actual: $role->getName());
        $this->assertSame(expected: 'new-role', actual: $role->getSlug());
    }
    
    /** @test */
    public function aRoleCanBeJSONSerialized(): void
    {
        $role = new Role(
            name: "Test Role",
            slug: "test-role"
        );
        $this->setByReflection(object: $role, property: 'id', value: 1);

        $this->assertInstanceOf(expected: Role::class, actual: $role);
        $this->assertSame(expected: 1, actual: $role->getId());
        $this->assertSame(expected: "Test Role", actual: $role->getName());
        $this->assertSame(expected: 'test-role', actual: $role->getSlug());

        $this->assertJsonStringEqualsJsonString(
            expectedJson: json_encode([
                "id" => 1,
                "name" => "Test Role",
                "slug" => "test-role"
            ]),
            actualJson: json_encode($role)
        );
    }
}
