<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Tests\DataFixtures\RoleFixture;
use App\Tests\DbKernelTestCase;

class RoleRepositoryTest extends DbKernelTestCase
{
    /** @test */
    public function aRoleCanBeFindBySLug(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $role = $roleRepository->findBySlug(slug: 'test-role');

        $this->assertInstanceOf(expected: Role::class, actual: $role);
    }
    /** @test */
    public function aRoleCanBeFindById(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $role = $roleRepository->findById(id: 1);

        $this->assertInstanceOf(expected: Role::class, actual: $role);
    }

    /** @test */
    public function aRoleNotFoundExceptionIsThrownWhenRoleNotFound(): void
    {
        $this->expectException(RoleNotFoundException::class);

        $this->databaseTool->loadFixtures(classNames: []);
        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $roleRepository->findById(id: 1);
    }
}
