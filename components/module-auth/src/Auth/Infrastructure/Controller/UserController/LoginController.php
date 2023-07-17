<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Auth\Application\Service\UserJWTTokenGeneratorService;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\Enums\RequestStatusEnum;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserLoginRequestValidator;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController implements PostControllerInterface
{
    private UserLoginRequestValidator $requestValidator;

    public function __construct(
        private readonly UserJWTTokenGeneratorService $tokenGeneratorService,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    #[Route(path: '/user/login', name: 'user_login')]
    public function __invoke(): JsonResponse
    {
        try {
            $user = $this->userRepository->findByEmail(email: $this->requestValidator->email);
        } catch (UserNotFoundException $e) {
            return new JsonResponse(data: [
                'status' => RequestStatusEnum::ERROR,
                'message' => $e->getMessage()
            ], status: JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $this->requestValidator->password)) {
            return new JsonResponse(data: [
                'status' => RequestStatusEnum::ERROR,
                'message' => 'Wrong combination'
            ], status: JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->tokenGeneratorService->generate($user);

        return new JsonResponse(data: [
            'ttl' => 3600,
            'type' => 'bearer',
            'token' => $token
        ]);
    }

    public function setData(RequestValidatorInterface $data): void
    {
        $this->requestValidator = $data;
    }
}
