<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\UserController;

use App\Auth\Application\Factory\UserFactory;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\UserController\RegisterController;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterControllerTest extends TestCase
{
    /** @test */
    public function aRequestIsCorrectlyHandled(): void
    {
        $controller = $this->getRegisterController();

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_OK, actual: $response->getStatusCode());
    }

    /** @test */
    public function aBadRequestIsReturnedWhenUserEmailExists(): void
    {
        $controller = $this->getRegisterController(userFound: true);

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_BAD_REQUEST, actual: $response->getStatusCode());
    }

    private function getRegisterController(bool $userFound = false): RegisterController
    {
        $registerController = new RegisterController(
            userRepository: $this->getUserRepositoryInterfaceMock(userFound: $userFound),
            entityManager: $this->createMock(EntityManagerInterface::class),
            userFactory: $this->getUserFactoryMock(create: !$userFound)
        );

        $userRegisterRequestValidator = new UserRegisterRequestValidator();
        $userRegisterRequestValidator->setData(data: [
            'name' => 'test de tester',
            'email' => 'test@test.nl',
            'password' => 'testtest'
        ]);

        $registerController->setData($userRegisterRequestValidator);

        return $registerController;
    }

    private function getUserFactoryMock(bool $create = true): UserFactory
    {
        $factory = $this->createMock(originalClassName: UserFactory::class);

        if ($create) {
            $factory->expects($this->once())
                ->method(constraint: 'create')
                ->willReturn($this->createMock(User::class));
        }

        return $factory;
    }

    private function getUserRepositoryInterfaceMock(bool $userFound = false): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        if ($userFound) {
            $userRepository->expects($this->once())
                ->method(constraint: 'findByEmail')
                ->willReturn(value: $this->createMock(User::class));
        } else {
            $userRepository->expects($this->once())
                ->method(constraint: 'findByEmail')
                ->willThrowException(new UserNotFoundException());
        }

        return $userRepository;
    }
}
