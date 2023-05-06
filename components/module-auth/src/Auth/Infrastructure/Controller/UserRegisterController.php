<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Factory\UserFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserRegisterController
{
    public function __construct(
        private readonly UserFactoryInterface $userFactory,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/register', name: 'UserRegister')]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode(json: $request->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return new JsonResponse(data: [], status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $userRepository->findByEmail($data['email']);

            return new JsonResponse(data: [], status: JsonResponse::HTTP_BAD_REQUEST);
        } catch (UserNotFoundException $userNotFoundException){
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

        return new JsonResponse(data: [], status: JsonResponse::HTTP_CREATED);
    }
}
