<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\UserController;

use App\Tests\DataFixtures\RoleFixture;
use App\Tests\DataFixtures\UserFixture;
use App\Tests\DbWebTestCase;

class AddRoleController extends DbWebTestCase
{
    /** @test */
    public function aUserAddRoleRequestCanBeHandled(): void
    {
        $this->databaseTool->loadFixtures(classNames: [
            UserFixture::class, RoleFixture::class
        ]);

        $this->client->request(
            method: 'POST',
            uri: '/user/add-role',
            content: json_encode(value: [
                'type' => 'UserAddRoleRequest',
                'data' => [
                    'user' => 1,
                    'role' => 1
                ]
            ])
        );

        $this->assertResponseIsSuccessful();
    }
}
