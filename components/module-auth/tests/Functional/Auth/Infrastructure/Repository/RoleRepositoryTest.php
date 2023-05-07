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
    public function aRoleCanBeFoundBySlug(): void
    {
        $this->databaseTool->loadFixtures([RoleFixture::class]);

        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get('test.' . RoleRepositoryInterface::class);

        $this->assertInstanceOf(Role::class, $roleRepository->findBySlug('test-role'));
    }

    /** @test */
    public function aExceptionIsThrownWhenRoleIsNotFound(): void
    {
        $this->expectException(RoleNotFoundException::class);
        $this->databaseTool->loadFixtures([]);

        /** @var RoleRepositoryInterface $roleRepository */
        $roleRepository = self::bootKernel()->getContainer()->get('test.' . RoleRepositoryInterface::class);

        $roleRepository->findBySlug('test-role');
    }
}
