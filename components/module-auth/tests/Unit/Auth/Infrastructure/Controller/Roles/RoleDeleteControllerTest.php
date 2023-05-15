<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\Roles\RoleDeleteController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoleDeleteControllerTest extends TestCase
{
    /** @test */
    public function aRoleCanBeDeleted(): void
    {
        $controller = new RoleDeleteController(
            entityManager: $this->getEntityManagerMock()
        );

        $response = $controller(
            request: $this->getRequestMock()
        );

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_NO_CONTENT, actual: $response->getStatusCode());
    }

    /** @test */
    public function aHTTP422IsReturnedOnInvalidJson(): void
    {
        $controller = new RoleDeleteController(
            entityManager: $this->createMock(EntityManagerInterface::class)
        );

        $response = $controller(
            request: $this->getRequestMock(invalid: true)
        );

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_UNPROCESSABLE_ENTITY, actual: $response->getStatusCode());
    }

    /** @test */
    public function aHTTP404IsReturnedOnRoleNotFound(): void
    {
        $controller = new RoleDeleteController(
            entityManager: $this->getEntityManagerMock(roleNotFound: true)
        );

        $response = $controller(
            request: $this->getRequestMock()
        );

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_BAD_REQUEST, actual: $response->getStatusCode());
    }

    private function getEntityManagerMock(bool $roleNotFound = false): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(Role::class)
            ->willReturn(value: $this->getRoleRepositoryMock($roleNotFound));

        if (!$roleNotFound) {
            $entityManager->expects($this->once())
                ->method(constraint: 'remove');
            $entityManager->expects($this->once())
                ->method(constraint: 'flush');
        }

        return $entityManager;
    }

    private function getRoleRepositoryMock(bool $roleNotFound = false): RoleRepositoryInterface
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        if ($roleNotFound) {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willThrowException(new RoleNotFoundException());
        } else {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willReturn($this->createMock(Role::class));
        }

        return $roleRepository;
    }

    private function getRequestMock(bool $invalid = false): Request
    {
        $request = $this->createMock(originalClassName: Request::class);

        $request->expects($this->once())
            ->method(constraint: 'isMethod')
            ->with('POST')
            ->willReturn(value: true);

        if ($invalid) {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: "invalid-json");
        } else {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(
                    value: json_encode(value: [
                        "slug" => "test-role"
                    ])
                );
        }

        return $request;
    }
}
