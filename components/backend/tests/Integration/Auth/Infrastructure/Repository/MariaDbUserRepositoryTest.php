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
        $this->databaseTool->loadFixtures([UserFixture::class]);
        /** @var UserRepositoryInterface $repository */
        $repository = self::bootKernel()->getContainer()->get(id: 'test.' . UserRepositoryInterface::class);

        $user = $repository->findByEmail('test@test.nl');

        $this->assertInstanceOf(expected: User::class, actual: $user);
    }
}
