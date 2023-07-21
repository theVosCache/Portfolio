<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\RoleController;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\RoleController\RoleCreateController;
use App\Auth\Infrastructure\Controller\RoleController\RoleUpdateController;
use App\Validator\Domain\RequestValidators\RoleRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoleUpdateControllerTest extends TestCase
{
    /** @test */
    public function aRoleUpdateCanBeHandled(): void
    {
        $controller = $this->getRoleUpdateController();

        $response = $controller(roleId: 1);

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_NO_CONTENT, actual: $response->getStatusCode());
    }

    private function getRoleUpdateController(): RoleUpdateController
    {
        $controller = new RoleUpdateController(
            roleRepository: $this->getRoleRepositoryMock(),
            entityManager: $this->getEntityManagerMock()
        );

        $validator = new RoleRequestValidator();
        $validator->setData(data: [
            'name' => 'test role',
            'slug' => 'test-role'
        ]);

        $controller->setData(data: $validator);

        return $controller;
    }

    private function getRoleRepositoryMock(): RoleRepositoryInterface
    {
        $repository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        $role = $this->createMock(originalClassName: Role::class);

        $role->expects($this->once())
            ->method(constraint: 'setName')
            ->willReturn(value: $role);
        $role->expects($this->once())
            ->method(constraint: 'setSlug')
            ->willReturn(value: $role);

        $repository->expects($this->once())
            ->method(constraint: 'findById')
            ->willReturn(value: $role);

        return $repository;
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method(constraint: 'flush');

        return $entityManager;
    }
}
