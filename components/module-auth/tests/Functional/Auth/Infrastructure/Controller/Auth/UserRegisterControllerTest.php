<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\Auth;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\UserFixture;

class UserRegisterControllerTest extends DbWebTestCase
{
    /** @test */
    public function aUserCanBeRegistered(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: file_get_contents(
                filename: __DIR__ . '/Request/user-register-request.json'
            )
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/Response/UserRegisterController/201-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aUserCantBeRegisteredIfEmailIsInUse(): void
    {
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);

        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: file_get_contents(
                filename: __DIR__ . '/Request/user-register-request.json'
            )
        );

        $this->assertResponseStatusCodeSame(expectedCode: 400);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/Response/UserRegisterController/400-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aHttp422IsReturnedOnInvalidJson(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: "invalid-json"
        );

        $this->assertResponseStatusCodeSame(expectedCode: 422);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/Response/422-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }
}