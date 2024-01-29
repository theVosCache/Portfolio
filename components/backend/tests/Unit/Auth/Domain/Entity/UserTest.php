<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

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
            name: 'Tester',
            email: 'test@test.nl',
            password: 'test'
        );
        $this->setByReflection(object: $user, property: 'id', value: 1);
        $this->setByReflection(object: $user, property: 'uuid', value: 'uuid');

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertSame(expected: 1, actual: $user->getId());
        $this->assertSame(expected: 'uuid', actual: $user->getUuid());
        $this->assertSame(expected: 'Tester', actual: $user->getName());
        $this->assertSame(expected: 'test@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'test', actual: $user->getPassword());
    }

    /** @test */
    public function aUserCanBeUpdated(): void
    {
        $user = new User(
            name: 'Tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $user->setName(name: 'Test')
            ->setEmail(email: 'no-reply@test.nl')
            ->setPassword(password: 'testtest');

        $this->assertSame(expected: 'Test', actual: $user->getName());
        $this->assertSame(expected: 'no-reply@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'testtest', actual: $user->getPassword());
    }
}
