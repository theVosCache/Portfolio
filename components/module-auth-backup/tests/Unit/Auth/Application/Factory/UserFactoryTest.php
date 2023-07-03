<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\Factory;

use App\Auth\Application\Factory\UserFactory;
use App\Auth\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    /** @test */
    public function aUserCanBeCreated(): void
    {
        $userFactory = new UserFactory(
            userPasswordHasher: $this->getPasswordHasherInterfaceMock()
        );

        $user = $userFactory->create(
            firstName: 'test',
            lastName: 'de Tester',
            email: 'test@tester.nl',
            password: 'test'
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertSame(expected: 'test', actual: $user->getFirstName());
        $this->assertSame(expected: 'de Tester', actual: $user->getLastName());
        $this->assertSame(expected: 'test@tester.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'hashed-password', actual: $user->getPassword());
    }

    private function getPasswordHasherInterfaceMock(): UserPasswordHasherInterface
    {
        $userPasswordHasher = $this->createMock(originalClassName: UserPasswordHasherInterface::class);

        $userPasswordHasher->expects($this->once())
            ->method(constraint: 'hashPassword')
            ->willReturn(value: 'hashed-password');

        return $userPasswordHasher;
    }
}
