<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\Roles;

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
                filename: __DIR__ . "/Requests/role-create-request.json"
            )
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/Responses/RoleCreateController/201-response.json",
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
                filename: __DIR__ . "/Requests/role-create-request.json"
            )
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/Responses/RoleCreateController/400-response.json",
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
            expectedFile: __DIR__ . "/Responses/422-response.json",
            actualJson: $this->client->getResponse()->getContent()
        );
    }
}
