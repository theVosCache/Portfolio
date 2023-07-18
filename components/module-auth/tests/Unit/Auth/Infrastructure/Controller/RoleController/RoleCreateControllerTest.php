<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\RoleController;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\RoleController\RoleCreateController;
use App\Validator\Domain\RequestValidatorInterface;
use App\Validator\Domain\RequestValidators\RoleCreateRequestValidator;
use App\Validator\Domain\WrongRequestValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoleCreateControllerTest extends TestCase
{
    /** @test */
    public function aRoleCreateRequestIsCorrectlyHandled(): void
    {
        $controller = $this->getRoleCreateController();

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_CREATED, actual: $response->getStatusCode());
    }

    /** @test */
    public function aRoleCreateRequestGives400IfRoleAlreadyExists(): void
    {
        $controller = $this->getRoleCreateController(roleFound: true);

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_BAD_REQUEST, actual: $response->getStatusCode());
    }

    /** @test */
    public function wrongRequestValidatorIsTrownWhenWrongValidatorIsAssigned(): void
    {
        $this->expectException(WrongRequestValidatorException::class);

        $controller = new RoleCreateController(
            roleRepository: $this->createMock(originalClassName: RoleRepositoryInterface::class),
            entityManager: $this->createMock(originalClassName: EntityManagerInterface::class)
        );

        $validator = $this->createMock(RequestValidatorInterface::class);

        $controller->setData($validator);
    }

    private function getRoleCreateController(bool $roleFound = false): RoleCreateController
    {
        $controller = new RoleCreateController(
            roleRepository: $this->getRoleRepositoryMock(roleFound: $roleFound),
            entityManager: $this->getEntityManagerMock(roleFound: $roleFound)
        );

        $validator = new RoleCreateRequestValidator();
        $validator->setData(data: [
            'name' => 'New Role',
            'slug' => 'new-role'
        ]);

        $controller->setData($validator);

        return $controller;
    }

    private function getRoleRepositoryMock(bool $roleFound): RoleRepositoryInterface
    {
        $repository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        if ($roleFound) {
            $repository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willReturn(value: $this->createMock(originalClassName: Role::class));
        } else {
            $repository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willThrowException(exception: new RoleNotFoundException());
        }

        return $repository;
    }

    private function getEntityManagerMock(bool $roleFound): EntityManagerInterface
    {
        $manager = $this->createMock(originalClassName: EntityManagerInterface::class);

        if (!$roleFound) {
            $manager->expects($this->once())
                ->method(constraint: 'persist');
            $manager->expects($this->once())
                ->method('flush');
        }

        return $manager;
    }
}
