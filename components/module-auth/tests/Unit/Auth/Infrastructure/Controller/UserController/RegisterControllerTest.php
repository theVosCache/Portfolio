<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\UserController;

use App\Auth\Infrastructure\Controller\UserController\RegisterController;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
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
        $this->assertSame(expected: JsonResponse::HTTP_CREATED, actual: $response->getStatusCode());
    }

    private function getRegisterController(): RegisterController
    {
        $registerController = new RegisterController();

        $userRegisterRequestValidator = new UserRegisterRequestValidator();
        $userRegisterRequestValidator->setData(data: [
            'name' => 'test de tester',
            'email' => 'test@test.nl',
            'password' => 'testtest'
        ]);

        $registerController->setData($userRegisterRequestValidator);

        return $registerController;
    }
}
