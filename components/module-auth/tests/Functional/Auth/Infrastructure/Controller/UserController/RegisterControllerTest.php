<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\UserController;

use App\Tests\DbWebTestCase;

class RegisterControllerTest extends DbWebTestCase
{
    /** @test */
    public function aUserCanBeRegistered(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(method: 'POST', uri: "/user/register", content: json_encode(value: [
            'type' => 'UserRegisterRequest',
            'data' => [
                'name' => 'test de tester',
                'email' => 'test@test.nl',
                'password' => 'testtest'
            ]
        ]));

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $responseData = json_decode(json: $response->getContent(), associative: true);

        $this->assertArrayHasKey(key: 'status', array: $responseData);
    }
}
