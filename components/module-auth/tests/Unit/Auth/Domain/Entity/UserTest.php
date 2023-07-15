<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function aUserCanBeCreatedAndValuesCanBeRetrieved(): void
    {
        $user = new User(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertSame(expected: 'test de tester', actual: $user->getName());
        $this->assertSame(expected: 'test@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'test', actual: $user->getPassword());
    }

    /** @test */
    public function aUserCanBeUpdated(): void
    {
        $user = new User(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $user->setName('bug de debug')
            ->setEmail('de@bug.nl')
            ->setPassword('testtest');

        $this->assertSame(expected: 'bug de debug', actual: $user->getName());
        $this->assertSame(expected: 'de@bug.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'testtest', actual: $user->getPassword());
    }
}
