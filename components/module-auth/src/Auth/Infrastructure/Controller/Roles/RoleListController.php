<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoleListController extends AbstractBaseController
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {
    }

    #[Route(path: '/roles', name: 'RoleList')]
    public function __invoke(): JsonResponse
    {
        $roles = $this->roleRepository->list();

        return $this->buildSuccessResponse(
            message: "Role List",
            data: [
                'roles' => $roles
            ]
        );
    }
}
