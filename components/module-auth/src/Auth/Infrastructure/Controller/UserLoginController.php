<?php

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserLoginController extends AbstractBaseController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JWTTokenManagerInterface $JWTTokenManager
    )
    {
    }

    #[Route(path: '/login', name: 'UserLogin')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest($request);
        if ($data instanceof JsonResponse){
            return $data;
        }

        try {
            $user = $this->userRepository->findByEmail($data['email']);
        } catch (UserNotFoundException $e) {
            return $this->buildErrorResponse(
                message: $e->getMessage(),
                statusCode: JsonResponse::HTTP_NOT_FOUND
            );
        }

        if (!$this->userPasswordHasher->isPasswordValid(
            user: $user,
            plainPassword: $data['password']
        )){
            return $this->buildErrorResponse(
                message: "Incorrect Password",
                statusCode: JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        $token = $this->JWTTokenManager->createFromPayload(
            user: $user,
            payload: [
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ]);

        return $this->buildSuccessResponse(
            message: 'Login Successful',
            data: [
                'token' => $token
            ]
        );
    }
}