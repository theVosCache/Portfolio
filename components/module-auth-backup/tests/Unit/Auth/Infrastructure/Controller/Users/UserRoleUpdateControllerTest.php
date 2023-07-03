<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\Users;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\Users\UserRoleUpdateController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRoleUpdateControllerTest extends TestCase
{
    /** @test */
    public function aUserHasHisRolesUpdated(): void
    {
        $controller = new UserRoleUpdateController(
            entityManager: $this->getEntityManagerMock()
        );

        $response = $controller(request: $this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_NO_CONTENT, actual: $response->getStatusCode());
    }

    private function getEntityManagerMock(): EntityManagerInterface
    {
        $entityManager = $this->createMock(originalClassName: EntityManagerInterface::class);

        $entityManager->expects($this->once())->method(constraint: 'flush');
        $entityManager->expects($this->exactly(count: 2))
            ->method(constraint: 'getRepository')
            ->will($this->onConsecutiveCalls($this->getUserRepositoryMock(), $this->getRoleRepositoryMock()));

        return $entityManager;
    }

    private function getRoleRepositoryMock(): RoleRepositoryInterface
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        $roleRepository->expects($this->exactly(count: 2))
            ->method(constraint: 'findBySlug')
            ->willReturn(value: $this->createMock(Role::class));

        return $roleRepository;
    }

    private function getUserRepositoryMock(): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method(constraint: 'findByEmail')
            ->willReturn(value: $this->getUserMock());

        return $userRepository;
    }

    private function getUserMock(): User
    {
        $user = $this->createMock(originalClassName: User::class);

        $user->expects($this->once())
            ->method(constraint: 'hasRole')
            ->willReturn(value: false);

        $user->expects($this->once())
            ->method(constraint: 'addRole');

        $user->expects($this->once())
            ->method(constraint: 'getRoles')
            ->willReturn(['remove-role']);

        $user->expects($this->once())
            ->method(constraint: 'removeRole');

        return $user;
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
                        'userIdentifier' => 'test@test.nl',
                        'roles' => ['test-role']
                    ])
                );
        }

        return $request;
    }
}
