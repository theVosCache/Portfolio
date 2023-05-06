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

        $response = $controller($this->getUserLoginRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function a422IsReturnedOnInvalidJson(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->createMock(UserRepositoryInterface::class),
            userPasswordHasher: $this->createMock(UserPasswordHasherInterface::class),
            JWTTokenManager: $this->createMock(JWTTokenManagerInterface::class)
        );

        $response = $controller($this->getUserLoginRequest(true));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
    }

    /** @test */
    public function a404IsReturnedOnUserNotFound(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->getUserRepositoryMock(false),
            userPasswordHasher: $this->createMock(UserPasswordHasherInterface::class),
            JWTTokenManager: $this->createMock(JWTTokenManagerInterface::class)
        );

        $response = $controller($this->getUserLoginRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    /** @test */
    public function a401IsReturnedOnInvalidPassword(): void
    {
        $controller = new UserLoginController(
            userRepository: $this->getUserRepositoryMock(),
            userPasswordHasher: $this->getUserPasswordHasherMock(false),
            JWTTokenManager: $this->createMock(JWTTokenManagerInterface::class)
        );

        $response = $controller($this->getUserLoginRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(401, $response->getStatusCode());
    }

    private function getJWTTokenManagerMock(): JWTTokenManagerInterface|MockObject
    {
        $tokenManager = $this->createMock(JWTManager::class);

        $tokenManager->expects($this->once())
            ->method('createFromPayload')
            ->willReturn('token');

        return $tokenManager;
    }

    private function getUserPasswordHasherMock(bool $validPassword = true): UserPasswordHasherInterface
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $passwordHasher->expects($this->once())
            ->method('isPasswordValid')
            ->willReturn($validPassword);

        return $passwordHasher;
    }

    private function getUserRepositoryMock(bool $userIsFound = true): UserRepositoryInterface
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

    private function getUserLoginRequest(bool $invalid = false): Request
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
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ]));
        }

        return $request;
    }
}
