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

class RoleCreateController implements PostControllerInterface
{
    /** @var RoleRequestValidator $data */
    private RequestValidatorInterface $data;

    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/role/create', name: 'role_create')]
    public function __invoke(): JsonResponse
    {
        try {
            $this->roleRepository->findBySlug($this->data->slug);

            return new JsonResponse([
                'status' => RequestStatusEnum::ERROR,
                'message' => sprintf('Role with slug %s already exists', $this->data->slug)
            ], status: JsonResponse::HTTP_BAD_REQUEST);
        } catch (RoleNotFoundException) {
            // silent
        }

        $role = new Role(
            name: $this->data->name,
            slug: $this->data->slug
        );

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        return new JsonResponse(data: [], status: JsonResponse::HTTP_CREATED);
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
