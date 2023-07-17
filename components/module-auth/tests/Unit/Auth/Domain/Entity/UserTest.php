<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aUserCanBeCreatedAndValuesCanBeRetrieved(): void
    {
        $user = new User(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->setByReflection(object: $user, property: 'id', value: 1);

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertSame(expected: 1, actual: $user->getId());
        $this->assertSame(expected: 'test de tester', actual: $user->getName());
        $this->assertSame(expected: 'test@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'test', actual: $user->getPassword());
        $this->assertInstanceOf(expected: DateTime::class, actual: $user->getCreatedAt());
        $this->assertInstanceOf(expected: DateTime::class, actual: $user->getUpdatedAt());
    }

    /** @test */
    public function aUserCanBeUpdated(): void
    {
        $user = new User(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->setByReflection(object: $user, property: 'updatedAt', value: new DateTime(datetime: '2023-01-01 00:00:00'));

        $user->setName('bug de debug')
            ->setEmail('de@bug.nl')
            ->setPassword('testtest');

        $this->assertSame(expected: 'bug de debug', actual: $user->getName());
        $this->assertSame(expected: 'de@bug.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'testtest', actual: $user->getPassword());
        $this->assertNotSame(expected: '2023-01-01 00:00:00', actual: $user->getUpdatedAt()->format(format: 'Y-m-d H:i:s'));
    }
}
