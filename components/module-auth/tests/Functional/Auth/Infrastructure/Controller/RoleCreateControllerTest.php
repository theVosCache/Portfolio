<?php

namespace App\Tests\Functional\Auth\Infrastructure\Controller;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\RoleFixture;

class RoleCreateControllerTest extends DbWebTestCase
{
    /** @test */
    public function aRoleCanBeCreated(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);
        $this->client->request(
            method: "POST",
            uri: "/role/create",
            content: file_get_contents(
                filename: __DIR__ . "/../../../../02-requests/RoleCreateController/200-request.json"
            )
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/../../../../01-responses/RoleCreateController/201-response.json",
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aRoleCantBeCreatedWhenSlugAlreadyExists(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        $this->client->request(
            method: "POST",
            uri: "/role/create",
            content: file_get_contents(
                filename: __DIR__ . "/../../../../02-requests/RoleCreateController/200-request.json"
            )
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/../../../../01-responses/RoleCreateController/400-response.json",
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function a422IsReturnedOnInvalidJson(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        $this->client->request(
            method: "POST",
            uri: "/role/create",
            content: "invalid-json"
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/../../../../01-responses/RoleCreateController/422-response.json",
            actualJson: $this->client->getResponse()->getContent()
        );
    }
}