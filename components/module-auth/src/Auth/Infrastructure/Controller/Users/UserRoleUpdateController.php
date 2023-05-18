<?php

namespace App\Auth\Infrastructure\Controller\Users;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRoleUpdateController extends AbstractBaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = $this->entityManager->getRepository(Role::class);

        try {
            $user = $userRepository->findByEmail($data['email']);
        } catch (UserNotFoundException $e) {
            return $this->buildErrorResponse(
                message: $e->getMessage(),
                statusCode: JsonResponse::HTTP_NOT_FOUND
            );
        }

        foreach ($data['roles'] as $roleSlug) {
            try {
                $role = $roleRepository->findBySlug($roleSlug);
            } catch (RoleNotFoundException $e) {
                continue;
            }

            if ($user->hasRole($role)){
                $user->removeRole($role);
            } else {
                $user->addRole($role);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}