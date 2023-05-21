<?php

namespace App\Auth\Infrastructure\Controller\Users;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRoleUpdateController extends AbstractBaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $roleRepository = $this->entityManager->getRepository(Role::class);

        try {
            $user = $userRepository->findByEmail(email: $data['userIdentifier']);
        } catch (UserNotFoundException $exception) {
            return $this->buildErrorResponse(message: $exception->getMessage());
        }

        foreach ($data['roles'] as $roleSlug) {
            $role = $roleRepository->findBySlug(slug: $roleSlug);

            if (!$user->hasRole(role: $role)) {
                $user->addRole(role: $role);
            }
        }

        foreach ($user->getRoles() as $role) {
            if (!in_array(needle: $role, haystack: $data['roles'])){
                $roleTmp = $roleRepository->findBySlug(slug: $role);

                $user->removeRole(role: $roleTmp);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}