<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoleDeleteController extends AbstractBaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/role/delete', name: 'RoleDelete')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $roleRepository = $this->entityManager->getRepository(Role::class);

        try {
            $role = $roleRepository->findBySlug($data['slug']);
        } catch (RoleNotFoundException $roleNotFoundException) {
            return $this->buildErrorResponse(
                message: sprintf(
                    "Role Not Found: %s",
                    $roleNotFoundException->getMessage()
                )
            );
        }

        $this->entityManager->remove($role);
        $this->entityManager->flush();

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
