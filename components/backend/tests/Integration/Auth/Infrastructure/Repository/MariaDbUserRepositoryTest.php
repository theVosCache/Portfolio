<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
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
        $user = $this->getRepository()->findByEmail(email: 'test@test.nl');

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }

    /** @test */
    public function aUserNotFoundExceptionIsThrownWhenEmailIsNotFoundInTheDatabase(): void
    {
        $this->databaseTool->loadFixtures();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage("User with Email: test@test.nl is not found");

        $this->getRepository()->findByEmail(email: 'test@test.nl');
    }

    /** @test */
    public function aUserCanBeRetrievedByUuidFromTheDatabase(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);

        $user = $this->getRepository()->findByUuid(uuid: UserFixture::TEST_USER_UUID);

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }
    
    /** @test */
    public function aUserNotFoundExceptionIsThrownWhenUuidIsNotFound(): void
    {
        $this->databaseTool->loadFixtures();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage("User not found");

        $this->getRepository()->findByUuid(uuid: UserFixture::TEST_USER_UUID);
    }

    public function getRepository(): UserRepositoryInterface
    {
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);
        return $repository;
    }
}
