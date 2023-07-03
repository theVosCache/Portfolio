<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Factory\RoleFactoryInterface;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\Roles\RoleCreateController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoleCreateControllerTest extends TestCase
{
    /** @test */
    public function aRoleCreateRequestIsHandledCorrectly(): void
    {
        $controller = new RoleCreateController(
            entityManager: $this->getEntityManagerMock(),
            roleFactory: $this->getRoleFactoryMock()
        );

        $response = $controller(request: $this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_CREATED, actual: $response->getStatusCode());
    }

    /** @test */
    public function aHttp422ResponseIsReturnedOnInvalidJson(): void
    {
        $controller = new RoleCreateController(
            entityManager: $this->createMock(originalClassName: EntityManagerInterface::class),
            roleFactory: $this->createMock(originalClassName: RoleFactoryInterface::class)
        );

        $response = $controller(request: $this->getRequestMock(invalid: true));

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_UNPROCESSABLE_ENTITY, actual: $response->getStatusCode());
    }

    /** @test */
    public function aHttp400ResponseIsReturnedOnInvalidJson(): void
    {
        $controller = new RoleCreateController(
            entityManager: $this->getEntityManagerMockRoleFound(),
            roleFactory: $this->getRoleFactoryMock()
        );

        $response = $controller(request: $this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_BAD_REQUEST, actual: $response->getStatusCode());
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())->method(constraint: 'persist');
        $entityManager->expects($this->once())->method(constraint: 'flush');
        $entityManager->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(Role::class)
            ->willReturn(value: $this->getRoleRepositoryMock());

        return $entityManager;
    }

    private function getEntityManagerMockRoleFound(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method(constraint: 'getRepository')
            ->with(Role::class)
            ->willReturn(value: $this->getRoleRepositoryMock(roleNotFound: false));

        return $entityManager;
    }

    private function getRoleRepositoryMock(bool $roleNotFound = true): RoleRepositoryInterface
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        if ($roleNotFound) {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willThrowException(exception: new RoleNotFoundException());
        } else {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willReturn($this->createMock(originalClassName: Role::class));
        }

        return $roleRepository;
    }

    private function getRoleFactoryMock(): RoleFactoryInterface
    {
        $roleFactory = $this->createMock(originalClassName: RoleFactoryInterface::class);

        $roleFactory->expects($this->once())
            ->method(constraint: 'create')
            ->willReturn(value: $this->createMock(originalClassName: Role::class));

        return $roleFactory;
    }

    private function getRequestMock(bool $invalid = false): Request
    {
        $request = $this->createMock(originalClassName: Request::class);

        $request->expects($this->once())
            ->method('isMethod')
            ->with('POST')
            ->willReturn(true);

        if ($invalid) {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: "invalid-json");
        } else {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: json_encode(value: [
                    "name" => "New Role"
                ]));
        }

        return $request;
    }
}
