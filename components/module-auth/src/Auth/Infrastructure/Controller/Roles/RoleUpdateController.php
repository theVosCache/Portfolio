<?php

namespace App\Auth\Infrastructure\Controller\Roles;

use App\Auth\Application\Service\SlugService;
use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoleUpdateController extends AbstractBaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SlugService $slugService
    ) {
    }

    #[Route(path: '/role/update', name: 'RoleUpdate')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->getDataFromRequest(request: $request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $roleRepository = $this->entityManager->getRepository(Role::class);

        try {
            $role = $roleRepository->findBySlug(slug: $data['slug']);
        } catch (RoleNotFoundException $exception) {
            return $this->buildErrorResponse(
                message: $exception->getMessage(),
                statusCode: JsonResponse::HTTP_NOT_FOUND
            );
        }

        $role->setName(name: $data['name'])->setSlug(slug: $this->slugService->create(input: $data['name']));

        $this->entityManager->flush();

        return $this->buildSuccessResponse(
            message: "Update successful",
            data: [
                'role' => $role
            ]
        );
    }
}