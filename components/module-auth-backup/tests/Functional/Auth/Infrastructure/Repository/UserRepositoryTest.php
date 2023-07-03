<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Tests\DbKernelTestCase;
use App\Tests\Fixtures\UserFixture;

class UserRepositoryTest extends DbKernelTestCase
{
    /** @test */
    public function itCanFindAUserViaEmail(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $this->assertInstanceOf(expected: User::class, actual: $userRepository->findByEmail(email: 'test@test.nl'));
    }

    /** @test */
    public function itThrowsAExceptionIfUserIsNotFound(): void
    {
        $this->expectException(exception: UserNotFoundException::class);
        $this->databaseTool->loadFixtures(classNames: []);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $userRepository->findByEmail(email: 'test@test.nl');
    }
}
