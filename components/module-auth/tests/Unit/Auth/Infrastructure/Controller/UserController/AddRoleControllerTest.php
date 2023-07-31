<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller\UserController;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Controller\UserController\AddRoleController;
use App\Validator\Domain\RequestValidators\UserAddRoleRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddRoleControllerTest extends TestCase
{
    /** @test */
    public function aAddRoleCanBeHandled(): void
    {
        $controller = $this->getAddRoleController();

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_CREATED, actual: $response->getStatusCode());
    }

    private function getAddRoleController(): AddRoleController
    {
        $addRoleController = new AddRoleController(
            userRepository: $this->getUserRepositoryMock(),
            roleRepository: $this->getRoleRepositoryMock(),
            entityManager: $this->createMock(EntityManagerInterface::class)
        );

        $userAddRoleRequest = $this->getUserAddRoleRequestMock();
        $userAddRoleRequest->setData(data: [
            'user' => 1,
            'role' => 1
        ]);

        $addRoleController->setData(
            data: $userAddRoleRequest
        );

        return $addRoleController;
    }

    public function getUserRepositoryMock(): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method(constraint: 'findById')
            ->with(1)
            ->willReturn($this->createMock(User::class));

        return $userRepository;
    }

    public function getRoleRepositoryMock(): RoleRepositoryInterface
    {
        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        $roleRepository->expects($this->once())
            ->method(constraint: 'findById')
            ->with(1)
            ->willReturn($this->createMock(Role::class));

        return $roleRepository;
    }

    private function getUserAddRoleRequestMock(): UserAddRoleRequestValidator
    {
        $user = $this->createMock(originalClassName: User::class);
        $user->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method(constraint: 'findById')
            ->with(1)
            ->willReturn(value: $user);

        $role = $this->createMock(originalClassName: Role::class);
        $role->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $roleRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);
        $roleRepository->expects($this->once())
            ->method(constraint: 'findById')
            ->with(1)
            ->willReturn(value: $role);

        return new UserAddRoleRequestValidator(
            userRepository: $userRepository,
            roleRepository: $roleRepository
        );
    }
}
