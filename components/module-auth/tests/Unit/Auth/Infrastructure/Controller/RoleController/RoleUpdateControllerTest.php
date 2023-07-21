<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\RoleController;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\RoleController\RoleCreateController;
use App\Validator\Domain\RequestValidators\RoleRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RoleUpdateControllerTest extends TestCase
{
    /** @test */
    public function aRoleUpdateCanBeHandled(): void
    {
    }


    private function getRoleUpdateController(): RoleUpdateController
    {
        $controller = new RoleUpdateController(
            roleRepository: $this->getRoleRepositoryMock(),
            entityManger: $this->getEntityManagerMock()
        );

        $validator = new RoleRequestValidator();
        $validator->setData(data: [
            'name' => 'test role',
            'slug' => 'test-role'
        ]);

        $controller->setData($validator);

        return $controller;
    }

    private function getRoleRepositoryMock(): RoleRepositoryInterface
    {
        $repository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        $role = $this->createMock(Role::class);

        $role->expects($this->once())
            ->method(constraint: 'setName')
            ->willReturn($role);
        $role->expects($this->once())
            ->method(constraint: 'setSlug')
            ->willReturn($role);

        $repository->expects($this->once())
            ->method(constraint: 'findBySlug')
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
