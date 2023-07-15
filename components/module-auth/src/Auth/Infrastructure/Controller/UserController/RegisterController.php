<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Auth\Application\Factory\UserFactory;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController implements PostControllerInterface
{
    private UserRegisterRequestValidator $requestValidator;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $this->userRepository->findByEmail($this->requestValidator->email);

            return new JsonResponse(data: [
                'status' => 'error',
                'message' => 'User Already Registered'
            ], status: JsonResponse::HTTP_BAD_REQUEST);
        } catch (UserNotFoundException $e) {
            // silent
        }

        $user = $this->userFactory->create(
            name: $this->requestValidator->name,
            email: $this->requestValidator->email,
            password: $this->requestValidator->password
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(data: [], status: JsonResponse::HTTP_CREATED);
    }

    public function setData(RequestValidatorInterface $data): void
    {
        $this->requestValidator = $data;
    }
}
