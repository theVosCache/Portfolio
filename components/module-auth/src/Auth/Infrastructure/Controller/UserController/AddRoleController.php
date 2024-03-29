<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserRoleRequestValidator;
use App\Validator\Domain\WrongRequestValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AddRoleController implements PostControllerInterface
{
    /** @var UserRoleRequestValidator $data */
    private RequestValidatorInterface $data;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/user/add-role', name: 'user_add_role')]
    public function __invoke(): JsonResponse
    {
        $user = $this->userRepository->findById(id: $this->data->user);
        $role = $this->roleRepository->findById(id: $this->data->role);

        $user->addRole(role: $role);

        $this->entityManager->flush();

        return new JsonResponse(data: [], status: JsonResponse::HTTP_CREATED);
    }

    /** @throws WrongRequestValidatorException */
    public function setData(RequestValidatorInterface $data): void
    {
        if (!($data instanceof UserRoleRequestValidator)) {
            throw new WrongRequestValidatorException(
                message: sprintf(
                    "Wrong validator assign, expected UserAddRoleRequest got %s",
                    $data::class
                )
            );
        }

        $this->data = $data;
    }
}
