<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Factory\RoleFactoryInterface;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoleCreateController extends AbstractBaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RoleFactoryInterface $roleFactory
    ) {
    }

    #[Route(path: '/role/create', name: 'RoleCreate')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $role = $this->roleFactory->create($data['name']);

        try {
            $roleRepository = $this->entityManager->getRepository(Role::class);
            $roleRepository->findBySlug(slug: $role->getSlug());

            return $this->buildErrorResponse(message: sprintf(
                "Role with slug: %s already exists",
                $role->getSlug()
            ));
        } catch (RoleNotFoundException) {
            // Silent fail
        }

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        return $this->buildSuccessResponse(
            message: 'Role Created',
            statusCode: JsonResponse::HTTP_CREATED
        );
    }
}
