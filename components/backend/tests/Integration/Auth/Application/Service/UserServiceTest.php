<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Application\Service;

use App\Auth\Application\Service\UserService;
use App\Auth\Domain\Entity\User;
use App\Tests\DbKernelTestCase;

class UserServiceTest extends DbKernelTestCase
{
    /** @test */
    public function aUserCanBeCreatedAndStoredInTheDatabase(): void
    {
        $this->databaseTool->loadFixtures();
        $user = $this->getUserService()->create(
            name: 'Test',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertNotEmpty(actual: $user->getId());
        $this->assertNotEmpty(actual: $user->getUuid());
        $this->assertNotEmpty(actual: $user->getCreatedAt());
        $this->assertNotEmpty(actual: $user->getUpdatedAt());
    }

    private function getUserService(): UserService
    {
        /** @var UserService $userService */
        $userService = self::bootKernel()->getContainer()->get(id: 'test.' . UserService::class);
        return $userService;
    }
}
