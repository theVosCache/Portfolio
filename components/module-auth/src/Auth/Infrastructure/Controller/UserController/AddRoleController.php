<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\UserController;

use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\UserAddRoleRequestValidator;
use App\Validator\Domain\WrongRequestValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddRoleController implements PostControllerInterface
{
    /** @var UserAddRoleRequestValidator $data */
    private RequestValidatorInterface $data;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

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
        if (!($data instanceof UserAddRoleRequestValidator)) {
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
