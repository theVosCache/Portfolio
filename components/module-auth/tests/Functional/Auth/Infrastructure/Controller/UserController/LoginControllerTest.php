<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\UserController;

use App\Tests\DataFixtures\UserFixture;
use App\Tests\DbWebTestCase;

class LoginControllerTest extends DbWebTestCase
{
    /** @test */
    public function aUserCanBeSignedIn(): void
    {
        $this->databaseTool->loadFixtures([UserFixture::class]);

        $this->client->request(method: 'POST', uri: '/user/login', content: json_encode(value: [
            'type' => 'UserLoginRequest',
            'data' => [
                'email' => 'test@test.nl',
                'password' => 'testtest'
            ]
        ]));

        $this->assertResponseIsSuccessful();
    }
}
