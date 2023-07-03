<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Roles;

use App\Auth\Domain\Entity\Role;
use App\Auth\Infrastructure\Controller\Roles\RoleListController;
use App\Auth\Infrastructure\Repository\RoleRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoleListControllerTest extends TestCase
{
    /** @test */
    public function itListsAllRoles(): void
    {
        $controller = new RoleListController(
            roleRepository: $this->getRoleRepository()
        );

        $response = $controller();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    private function getRoleRepository(): RoleRepository
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepository::class);

        $roles = [
            $this->createMock(originalClassName: Role::class),
            $this->createMock(originalClassName: Role::class),
        ];

        $roleRepository->expects($this->once())
            ->method(constraint: 'list')
            ->willReturn($roles);

        return $roleRepository;
    }
}
