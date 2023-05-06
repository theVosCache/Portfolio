<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\UserFixture;

class UserRegisterControllerTest extends DbWebTestCase
{
    /** @test */
    public function aUserCanBeRegistered(): void
    {
        $this->databaseTool->loadFixtures([]);

        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: file_get_contents(
                __DIR__ . '/../../../../02-requests/UserRegisterPost.json'
            )
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../../../../01-responses/UserRegisterController/201-response.json',
            $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aUserCantBeRegisteredIfEmailIsInUse(): void
    {
        $this->databaseTool->loadFixtures([UserFixture::class]);

        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: file_get_contents(
                __DIR__ . '/../../../../02-requests/UserRegisterPost.json'
            )
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../../../../01-responses/UserRegisterController/400-response.json',
            $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aHttp422IsReturnedOnInvalidJson(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/register',
            content: "invalid-json");

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../../../../01-responses/UserRegisterController/422-response.json',
            $this->client->getResponse()->getContent()
        );
    }

}