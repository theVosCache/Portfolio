<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Tests\DataFixtures\UserFixture;
use App\Tests\DbKernelTestCase;

class UserRepositoryTest extends DbKernelTestCase
{
    /** @test */
    public function aUserCanBeFoundByEmail(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $user = $repository->findByEmail(email: 'test@test.nl');

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }

    /** @test */
    public function aExceptionIsThrownWhenUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->databaseTool->loadFixtures(classNames: []);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $repository->findByEmail(email: 'test@test.nl');
    }

    /** @test */
    public function aUserCanBeFoundById(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $user = $repository->findById(id: 1);

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }

    /** @test */
    public function aExceptionIsThrownWhenUserNotFoundById(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->databaseTool->loadFixtures(classNames: []);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $repository->findById(id: 1);
    }
}
