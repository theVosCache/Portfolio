<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Tests\DataFixtures\UserFixture;
use App\Tests\DbKernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MariaDbUserRepositoryTest extends DbKernelTestCase
{
    /** @test */
    public function aUserCanBeRetrievedByEmailFromTheDatabase(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $user = $repository->findByEmail(email: 'test@test.nl');

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }

    /** @test */
    public function aUserCanBeRetrievedByUuidFromTheDatabase(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $user = $repository->findByUuid(uuid: UserFixture::TEST_USER_UUID);

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }
}
