<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Auth;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Factory\UserFactoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\Auth\UserRegisterController;
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

        $response = $controller(request: $this->getUserRegisterRequest());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_CREATED, actual: $response->getStatusCode());
    }

    /** @test */
    public function aUnprocessableResponseIsReturnedOnInvalidJson(): void
    {
        $controller = new UserRegisterController(
            userFactory: $this->createMock(originalClassName: UserFactoryInterface::class),
            entityManager: $this->createMock(originalClassName: EntityManagerInterface::class)
        );

        $response = $controller(request: $this->getUserRegisterRequest(invalid: true));

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_UNPROCESSABLE_ENTITY, actual: $response->getStatusCode());
    }

    /** @test */
    public function aBadRequestResponseIsReturnedOnAlreadyExistingUser(): void
    {
        $controller = new UserRegisterController(
            userFactory: $this->createMock(originalClassName: UserFactoryInterface::class),
            entityManager: $this->getEntityManagerMockWhereUserIsFound()
        );

        $response = $controller(request: $this->getUserRegisterRequest());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_BAD_REQUEST, actual: $response->getStatusCode());
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())->method(constraint: 'persist');
        $entityManager->expects($this->once())->method(constraint: 'flush');
        $entityManager->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(User::class)
            ->willReturn(value: $this->getUserRepositoryMock());

        return $entityManager;
    }

    private function getEntityManagerMockWhereUserIsFound(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(User::class)
            ->willReturn(value: $this->getUserRepositoryMock(userIsFound: true));

        return $entityManager;
    }

    private function getUserFactoryMock(): UserFactoryInterface
    {
        $factory = $this->createMock(originalClassName: UserFactoryInterface::class);

        $factory->expects($this->once())
            ->method(constraint: 'create')
            ->willReturn(value: $this->createMock(originalClassName: User::class));

        return $factory;
    }

    private function getUserRepositoryMock(bool $userIsFound = false): UserRepositoryInterface
    {
        $repository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        if ($userIsFound) {
            $repository->expects($this->once())
                ->method(constraint: 'findByEmail')
                ->willReturn(value: $this->createMock(originalClassName: User::class));
        } else {
            $repository->expects($this->once())
                ->method(constraint: 'findByEmail')
                ->willThrowException(exception: new UserNotFoundException());
        }

        return $repository;
    }

    private function getUserRegisterRequest(bool $invalid = false): Request
    {
        $request = $this->createMock(originalClassName: Request::class);
        
        $request->expects($this->once())
            ->method('isMethod')
            ->with('POST')
            ->willReturn(true);

        if ($invalid) {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: "invalid-json");
        } else {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: json_encode(value: [
                    'firstName' => 'Tester',
                    'lastName' => 'de Tester',
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ]));
        }

        return $request;
    }
}
