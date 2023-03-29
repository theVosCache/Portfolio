<?php

namespace App\Tests\Unit\Auth\Domain\Entity;

use App\Auth\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function aUserCanBeCreated(): void
    {
        $user = new User(
            firstName: 'Test',
            lastName: 'de Tester',
            email: 'test@test.nl'
        );

        $this->assertSame('Test', $user->getFirstName());
        $this->assertSame('de Tester', $user->getLastName());
        $this->assertSame('test@test.nl', $user->getEmail());
    }
}