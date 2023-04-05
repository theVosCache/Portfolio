<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /** @var AbstractDatabaseTool */
    private $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /** @test */
    public function itCanFindAUserViaEmail(): void
    {
        $this->databaseTool->loadFixtures([UserFixture::class]);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = self::bootKernel()->getContainer()->get('test.' . UserRepositoryInterface::class);

        $this->assertInstanceOf(User::class, $userRepository->findByEmail('test@test.nl'));
    }

    /** @test */
    public function itThrowsAExceptionIfUserIsNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->databaseTool->loadFixtures([]);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = self::bootKernel()->getContainer()->get('test.' . UserRepositoryInterface::class);

        $userRepository->findByEmail('test@test.nl');
    }
}
