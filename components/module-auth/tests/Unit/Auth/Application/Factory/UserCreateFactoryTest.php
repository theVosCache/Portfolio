<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\Factory;

use App\Auth\Application\Factory\UserFactory;
use App\Auth\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreateFactoryTest extends TestCase
{
    /** @test */
    public function aUserCanBeCreated(): void
    {
        $userFactory = new UserFactory(
            hasher: $this->getUserPasswordHasherInterface()
        );

        $user = $userFactory->create(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'testtest'
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertSame(expected: 'test de tester', actual: $user->getName());
        $this->assertSame(expected: 'test@test.nl', actual: $user->getEmail());
        $this->assertSame(expected: 'encoded-password', actual: $user->getPassword());
    }

    private function getUserPasswordHasherInterface(): UserPasswordHasherInterface
    {
        $hasher = $this->createMock(originalClassName: UserPasswordHasherInterface::class);

        $hasher->expects($this->once())
            ->method(constraint: 'hashPassword')
            ->willReturn(value: 'encoded-password');

        return $hasher;
    }
}
