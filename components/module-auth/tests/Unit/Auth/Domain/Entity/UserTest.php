<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aUserCanBeCreated(): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );
        $this->setByReflection(object: $user, property: 'id', value: 1);

        $user->setPassword(password: 'password');

        $this->assertSame(expected: 1, actual: $user->getId());
        $this->assertSame(expected: 'Test', actual: $user->getFirstName());
        $this->assertSame(expected: 'de Tester', actual: $user->getLastName());
        $this->assertSame(expected: 'test@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'password', actual: $user->getPassword());
    }

    /** @test */
    public function aUserCanBeUpdated(): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );
        $user->setPassword(password: 'password');

        $user->setFirstName(firstName: 'tester')
            ->setLastName(lastName: 'last name')
            ->setEmail(email: 'me@test.nl')
            ->setPassword(password: 'new-password');

        $this->assertSame(expected: 'tester', actual: $user->getFirstName());
        $this->assertSame(expected: 'last name', actual: $user->getLastName());
        $this->assertSame(expected: 'me@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'new-password', actual: $user->getPassword());
    }

    /** @test */
    public function aUserHasAConnectionToRoles(): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );

        $role = new Role(
            name: 'Test Role',
            slug: 'test-role'
        );

        $this->assertCount(
            expectedCount: 0,
            haystack: $user->getRolesRelations()
        );

        $user->addRole(role: $role);

        $this->assertCount(
            expectedCount: 1,
            haystack: $user->getRolesRelations()
        );
        $this->assertTrue(condition: $user->hasRole(role: $role));

        $user->removeRole($role);


        $this->assertCount(
            expectedCount: 0,
            haystack: $user->getRolesRelations()
        );
        $this->assertFalse(condition: $user->hasRole(role: $role));
    }
}
