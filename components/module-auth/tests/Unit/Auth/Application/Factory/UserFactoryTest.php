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
            $this->getPasswordHasherInterfaceMock()
        );

        $user = $userFactory->create(
            firstName: 'test',
            lastName: 'de Tester',
            email: 'test@tester.nl',
            password: 'test'
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('test', $user->getFirstName());
        $this->assertSame('de Tester', $user->getLastName());
        $this->assertSame('test@tester.nl', $user->getEmail());
        $this->assertSame('hashed-password', $user->getPassword());
    }

    private function getPasswordHasherInterfaceMock(): UserPasswordHasherInterface
    {
        $userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $userPasswordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed-password');

        return $userPasswordHasher;
    }
}
