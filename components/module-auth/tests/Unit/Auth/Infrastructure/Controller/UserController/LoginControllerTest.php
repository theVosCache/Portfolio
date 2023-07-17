<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\UserController;

use App\Auth\Application\Service\UserJWTTokenGeneratorService;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\UserController\LoginController;
use App\Validator\Domain\RequestValidators\UserLoginRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends TestCase
{
    /** @test */
    public function aUserLoginRequestIsCorrectlyHandled(): void
    {
        $controller = $this->getLoginController();

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_OK, actual: $response->getStatusCode());

        $array = json_decode(json: $response->getContent(), associative: true);

        $this->assertIsArray(actual: $array);
        $this->assertArrayHasKey(key: 'ttl', array: $array);
        $this->assertArrayHasKey(key: 'type', array: $array);
        $this->assertArrayHasKey(key: 'token', array: $array);
    }

    private function getLoginController(): LoginController
    {
        $controller = new LoginController(
            tokenGeneratorService: $this->getUserJwtTokenGeneratorServiceMock(),
            userRepository: $this->getUserRepositoryInterfaceMock(),
            userPasswordHasher: $this->getUserPasswordHasherInterface()
        );

        $loginRequestValidator = new UserLoginRequestValidator();
        $loginRequestValidator->setData(data: [
            'email ' => 'test@test.nl',
            'password' => 'testtest'
        ]);

        $controller->setData($loginRequestValidator);

        return $controller;
    }

    private function getUserJwtTokenGeneratorServiceMock(): UserJWTTokenGeneratorService
    {
        $service = $this->createMock(originalClassName: UserJWTTokenGeneratorService::class);

        $service->expects($this->once())
            ->method(constraint: 'generate')
            ->willReturn(value: 'encoded-token');

        return $service;
    }

    private function getUserRepositoryInterfaceMock(): UserRepositoryInterface
    {
        $repository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        $repository->expects($this->once())
            ->method(constraint: 'findByEmail')
            ->willReturn($this->createMock(User::class));

        return $repository;
    }

    private function getUserPasswordHasherInterface(): UserPasswordHasherInterface
    {
        $hasher = $this->createMock(originalClassName: UserPasswordHasherInterface::class);

        $hasher->expects($this->once())
            ->method(constraint: 'isPasswordValid')
            ->willReturn(value: true);

        return $hasher;
    }
}
