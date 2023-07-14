<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Auth;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Factory\UserFactoryInterface;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserRegisterController extends AbstractBaseController
{
    public function __construct(
        private readonly UserFactoryInterface $userFactory,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/register', name: 'UserRegister')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $userRepository->findByEmail(email: $data['email']);

            return $this->buildErrorResponse(message: sprintf(
                "User with email: %s already exists",
                $data['email']
            ));
        } catch (UserNotFoundException) {
            // silent fail
        }

        $user = $this->userFactory->create(
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            email: $data['email'],
            password: $data['password']
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->buildSuccessResponse(
            message: 'User Created',
            statusCode: JsonResponse::HTTP_CREATED
        );
    }
}