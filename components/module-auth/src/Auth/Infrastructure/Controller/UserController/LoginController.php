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
use App\Validator\Domain\WrongRequestValidatorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController implements PostControllerInterface
{
    /** @var UserLoginRequestValidator $data */
    private RequestValidatorInterface $data;

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
            $user = $this->userRepository->findByEmail(email: $this->data->email);
        } catch (UserNotFoundException $e) {
            return new JsonResponse(data: [
                'status' => RequestStatusEnum::ERROR,
                'message' => $e->getMessage()
            ], status: JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $this->data->password)) {
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

    /** @throws WrongRequestValidatorException */
    public function setData(RequestValidatorInterface $data): void
    {
        if (!($data instanceof UserLoginRequestValidator)) {
            throw new WrongRequestValidatorException(
                message: sprintf(
                    "Wrong validator assign, expected LoginRequest got %s",
                    $data::class
                )
            );
        }

        $this->data = $data;
    }
}
