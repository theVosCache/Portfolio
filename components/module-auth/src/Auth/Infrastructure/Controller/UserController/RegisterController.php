<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController implements PostControllerInterface
{
    private UserRegisterRequestValidator $requestValidator;

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(data: [], status: JsonResponse::HTTP_CREATED);
    }

    public function setData(RequestValidatorInterface $data): void
    {
        $this->requestValidator = $data;
    }
}
