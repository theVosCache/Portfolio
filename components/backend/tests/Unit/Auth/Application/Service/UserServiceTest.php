<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\Service;

use App\Auth\Application\Service\UserService;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserEmailAlreadyExistsException;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    /** @test */
    public function aUserIsCreatedAndStoredInTheDatabase(): void
    {
        $userService = new UserService(
            entityManager: $this->getEntityManagerMock()
        );

        $user = $userService->create(
            name: 'Tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }

    /** @test */
    public function aUserIsNotCreatedWhenAUserWithEmailAlreadyExistsInTheDatabase(): void
    {
        $this->expectException(UserEmailAlreadyExistsException::class);
        $userService = new UserService(
            entityManager: $this->getEntityManagerMockThatDoesntSaveAUser()
        );

        $userService->create(
            name: 'Tester',
            email: 'test@test.nl',
            password: 'test'
        );
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManger = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManger->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(User::class)
            ->willReturn(value: $this->getUserRepositoryMock());

        $entityManger->expects($this->once())
            ->method(constraint: 'persist');

        $entityManger->expects($this->once())
            ->method(constraint: 'flush');

        $entityManger->expects($this->once())
            ->method('refresh');
        
        return $entityManger;
    }

    private function getEntityManagerMockThatDoesntSaveAUser(): EntityManagerInterface
    {
        $entityManger = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManger->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(User::class)
            ->willReturn(value: $this->getUserRepositoryMockThatReturnsAUserMock());

        $entityManger->expects($this->never())
            ->method(constraint: 'persist');

        $entityManger->expects($this->never())
            ->method(constraint: 'flush');

        $entityManger->expects($this->never())
            ->method('refresh');

        return $entityManger;
    }

    private function getUserRepositoryMock(): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method(constraint: 'findByEmail')
            ->willThrowException(exception: new UserNotFoundException());

        return $userRepository;
    }

    private function getUserRepositoryMockThatReturnsAUserMock(): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method(constraint: 'findByEmail')
            ->willReturn($this->createMock(User::class));

        return $userRepository;
    }
}
