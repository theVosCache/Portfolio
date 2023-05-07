<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\UserLoginController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserLoginControllerTest extends TestCase
{
    /** @test */
    public function aUserCanSignIn(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->getUserRepositoryMock(),
            userPasswordHasher: $this->getUserPasswordHasherMock(),
            JWTTokenManager: $this->getJWTTokenManagerMock()
        );

        $response = $controller(request: $this->getUserLoginRequest());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: 200, actual: $response->getStatusCode());
    }

    /** @test */
    public function a422IsReturnedOnInvalidJson(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->createMock(originalClassName: UserRepositoryInterface::class),
            userPasswordHasher: $this->createMock(originalClassName: UserPasswordHasherInterface::class),
            JWTTokenManager: $this->createMock(originalClassName: JWTTokenManagerInterface::class)
        );

        $response = $controller(request: $this->getUserLoginRequest(invalid: true));

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: 422, actual: $response->getStatusCode());
    }

    /** @test */
    public function a404IsReturnedOnUserNotFound(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->getUserRepositoryMock(userIsFound: false),
            userPasswordHasher: $this->createMock(originalClassName: UserPasswordHasherInterface::class),
            JWTTokenManager: $this->createMock(originalClassName: JWTTokenManagerInterface::class)
        );

        $response = $controller(request: $this->getUserLoginRequest());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: 404, actual: $response->getStatusCode());
    }

    /** @test */
    public function a401IsReturnedOnInvalidPassword(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->getUserRepositoryMock(),
            userPasswordHasher: $this->getUserPasswordHasherMock(validPassword: false),
            JWTTokenManager: $this->createMock(originalClassName: JWTTokenManagerInterface::class)
        );

        $response = $controller(request: $this->getUserLoginRequest());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: 401, actual: $response->getStatusCode());
    }

    private function getJWTTokenManagerMock(): JWTTokenManagerInterface|MockObject
    {
        $tokenManager = $this->createMock(originalClassName: JWTManager::class);

        $tokenManager->expects($this->once())
            ->method(constraint: 'createFromPayload')
            ->willReturn(value: 'token');

        return $tokenManager;
    }

    private function getUserPasswordHasherMock(bool $validPassword = true): UserPasswordHasherInterface
    {
        $passwordHasher = $this->createMock(originalClassName: UserPasswordHasherInterface::class);

        $passwordHasher->expects($this->once())
            ->method(constraint: 'isPasswordValid')
            ->willReturn(value: $validPassword);

        return $passwordHasher;
    }

    private function getUserRepositoryMock(bool $userIsFound = true): UserRepositoryInterface
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

    private function getUserLoginRequest(bool $invalid = false): Request
    {
        $request = $this->createMock(originalClassName: Request::class);

        if ($invalid) {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: "invalid-json");
        } else {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: json_encode(value: [
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ]));
        }

        return $request;
    }
}
