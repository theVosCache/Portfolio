<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Factory\UserFactoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\UserRegisterController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterControllerTest extends TestCase
{
    /** @test */
    public function aUserCanBeRegistered(): void
    {
        $controller = new UserRegisterController(
            userFactory: $this->getUserFactoryMock(),
            entityManager: $this->getEntityManagerMock()
        );

        $response = $controller($this->getUserRegisterRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    /** @test */
    public function aBadRequestResponseIsReturnedOnInvalidJson(): void
    {
        $controller = new UserRegisterController(
            userFactory: $this->createMock(UserFactoryInterface::class),
            entityManager: $this->createMock(EntityManagerInterface::class)
        );

        $response = $controller($this->getUserRegisterRequest(true));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /** @test */
    public function aBadRequestResponseIsReturnedOnAlreadyExistingUser(): void
    {
        $controller = new UserRegisterController(
            userFactory: $this->createMock(UserFactoryInterface::class),
            entityManager: $this->getEntityManagerMockWhereUserIsFound()
        );

        $response = $controller($this->getUserRegisterRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->getUserRepositoryMock());

        return $entityManager;
    }

    private function getEntityManagerMockWhereUserIsFound(): EntityManagerInterface
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->getUserRepositoryMock(true));

        return $entityManager;
    }

    private function getUserFactoryMock(): UserFactoryInterface
    {
        $factory = $this->createMock(UserFactoryInterface::class);

        $factory->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(User::class));

        return $factory;
    }

    private function getUserRepositoryMock(bool $userIsFound = false): UserRepositoryInterface
    {
        $repository = $this->createMock(UserRepositoryInterface::class);

        if ($userIsFound) {
            $repository->expects($this->once())
                ->method('findByEmail')
                ->willReturn($this->createMock(User::class));
        } else {
            $repository->expects($this->once())
                ->method('findByEmail')
                ->willThrowException(new UserNotFoundException());
        }

        return $repository;
    }

    private function getUserRegisterRequest(bool $invalid = false): Request
    {
        $request = $this->createMock(Request::class);

        if ($invalid) {
            $request->expects($this->once())
                ->method('getContent')
                ->willReturn("invalid-json");
        } else {
            $request->expects($this->once())
                ->method('getContent')
                ->willReturn(json_encode([
                    'firstName' => 'Tester',
                    'lastName' => 'de Tester',
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ]));
        }

        return $request;
    }
}
