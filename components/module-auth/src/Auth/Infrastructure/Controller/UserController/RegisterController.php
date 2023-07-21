<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Auth\Application\Factory\UserFactory;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\Enums\RequestStatusEnum;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use App\Validator\Domain\WrongRequestValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController implements PostControllerInterface
{
    /** @var UserRegisterRequestValidator $data */
    private RequestValidatorInterface $data;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory
    ) {
    }

    #[Route(path: '/user/register', name: 'user_register')]
    public function __invoke(): JsonResponse
    {
        try {
            $this->userRepository->findByEmail($this->data->email);

            return new JsonResponse(data: [
                'status' => RequestStatusEnum::ERROR,
                'message' => 'User Already Registered'
            ], status: JsonResponse::HTTP_BAD_REQUEST);
        } catch (UserNotFoundException $e) {
            // silent
        }

        $user = $this->userFactory->create(
            name: $this->data->name,
            email: $this->data->email,
            password: $this->data->password
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(data: [
            'status' => RequestStatusEnum::OK,
            'message' => 'User Created',
            'user' => $user
        ], status: JsonResponse::HTTP_OK);
    }

    /** @throws WrongRequestValidatorException */
    public function setData(RequestValidatorInterface $data): void
    {
        if (!($data instanceof UserRegisterRequestValidator)) {
            throw new WrongRequestValidatorException(
                message: sprintf(
                    "Wrong validator assign, expected RegisterRequest got %s",
                    $data::class
                )
            );
        }

        $this->data = $data;
    }
}
