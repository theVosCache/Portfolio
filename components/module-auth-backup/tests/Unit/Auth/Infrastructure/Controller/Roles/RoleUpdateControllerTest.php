<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Roles;

use App\Auth\Application\Service\SlugService;
use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Infrastructure\Controller\Roles\RoleUpdateController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoleUpdateControllerTest extends TestCase
{
    /** @test */
    public function aRoleCanBeUpdated(): void
    {
        $controller = new RoleUpdateController(
            entityManager: $this->getEntityManagerMock(),
            slugService: new SlugService()
        );

        $response = $controller($this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    /** @test */
    public function aRoleCantBeUpdatedWhenRoleNotFound(): void
    {
        $controller = new RoleUpdateController(
            entityManager: $this->getEntityManagerMock(roleFound: false),
            slugService: new SlugService()
        );

        $response = $controller($this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function a422IsReturnedOnInvalidJson(): void
    {
        $controller = new RoleUpdateController(
            entityManager: $this->createMock(EntityManagerInterface::class),
            slugService: new SlugService()
        );

        $response = $controller($this->getRequestMock(invalid: true));

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    private function getEntityManagerMock(bool $roleFound = true): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        if ($roleFound) {
            $entityManager->expects($this->once())
                ->method(constraint: 'getRepository')
                ->with(Role::class)
                ->willReturn($this->getRoleRepositoryMock(roleFound: true));

            $entityManager->expects($this->once())
                ->method(constraint: 'flush');
        } else {
            $entityManager->expects($this->once())
                ->method(constraint: 'getRepository')
                ->with(Role::class)
                ->willReturn($this->getRoleRepositoryMock(roleFound: false));
        }

        return $entityManager;
    }

    private function getRoleRepositoryMock(bool $roleFound): RoleRepositoryInterface
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        if ($roleFound) {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willReturn($this->createMock(originalClassName: Role::class));
        } else {
            $roleRepository->expects($this->once())
                ->method(constraint: 'findBySlug')
                ->willThrowException(new RoleNotFoundException());
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
                        'name' => 'New Role',
                        'slug' => 'test-role'
                    ])
                );
        }

        return $request;
    }
}
