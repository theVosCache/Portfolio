<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\RoleController;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Validator\Domain\Enums\RequestStatusEnum;
use App\Validator\Domain\PostControllerInterface;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\RoleRequestValidator;
use App\Validator\Domain\WrongRequestValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoleUpdateController implements PostControllerInterface
{
    /** @var RoleRequestValidator $data */
    private RequestValidatorInterface $data;

    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/role/update/{roleId}', name: 'role_update')]
    public function __invoke(int $roleId): JsonResponse
    {
        try {
            $role = $this->roleRepository->findById(id: $roleId);

            $role->setName($this->data->name)
                ->setSlug($this->data->slug);

            $this->entityManager->flush();
        } catch (RoleNotFoundException) {
            return new JsonResponse([
                'status' => RequestStatusEnum::ERROR,
                'message' => sprintf('Role with id %s does not exists', 0)
            ], status: JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(data: [], status: JsonResponse::HTTP_NO_CONTENT);
    }

    public function setData(RequestValidatorInterface $data): void
    {
        if (!($data instanceof RoleRequestValidator)) {
            throw new WrongRequestValidatorException(
                message: sprintf(
                    "Wrong validator assign, expected RoleCreateRequest got %s",
                    $data::class
                )
            );
        }

        $this->data = $data;
    }
}
