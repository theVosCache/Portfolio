<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Tests\DbKernelTestCase;
use App\Tests\Fixtures\RoleFixture;

class RoleRepositoryTest extends DbKernelTestCase
{
    /** @test */
    public function allRolesCanBeListed(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);

        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $roles = $roleRepository->list();

        $this->assertIsArray(actual: $roles);
        $this->assertContainsOnly(type: Role::class, haystack: $roles);
    }
    
    /** @test */
    public function aRoleCanBeFoundBySlug(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);

        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $this->assertInstanceOf(expected: Role::class, actual: $roleRepository->findBySlug(slug: 'test-role'));
    }

    /** @test */
    public function aExceptionIsThrownWhenRoleIsNotFound(): void
    {
        $this->expectException(exception: RoleNotFoundException::class);
        $this->databaseTool->loadFixtures(classNames: []);

        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get(id: 'test.' . RoleRepositoryInterface::class);

        $roleRepository->findBySlug(slug: 'test-role');
    }
}
