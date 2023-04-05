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
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );
        $this->setByReflection($user, 'id', 1);

        $user->setPassword('password');

        $this->assertSame(1, $user->getId());
        $this->assertSame('Test', $user->getFirstName());
        $this->assertSame('de Tester', $user->getLastName());
        $this->assertSame('test@test.nl', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
    }

    /** @test */
    public function aUserCanBeUpdated(): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );
        $user->setPassword('password');

        $user->setFirstName('tester')
            ->setLastName('last name')
            ->setEmail('me@test.nl')
            ->setPassword('new-password');

        $this->assertSame('tester', $user->getFirstName());
        $this->assertSame('last name', $user->getLastName());
        $this->assertSame('me@test.nl', $user->getEmail());
        $this->assertSame('new-password', $user->getPassword());
    }
}
