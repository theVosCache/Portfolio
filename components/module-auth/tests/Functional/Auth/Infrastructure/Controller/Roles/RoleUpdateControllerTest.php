<?php

namespace App\Tests\Functional\Auth\Infrastructure\Controller\Roles;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\RoleFixture;

class RoleUpdateControllerTest extends DbWebTestCase
{
    /** @test */
    public function aRoleCanBeUpdated(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);

        $this->client->request(
            method: 'POST',
            uri: '/role/update',
            content: file_get_contents(
                filename: __DIR__ . '/../../../../../02-requests/RoleUpdateController/Request.json'
            )
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/../../../../../01-responses/RoleUpdateController/200-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aRoleCantBeUpdatedWhenRoleNotFound(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(
            method: 'POST',
            uri: '/role/update',
            content: file_get_contents(
                filename: __DIR__ . '/../../../../../02-requests/RoleUpdateController/Request.json'
            )
        );

        $this->assertResponseStatusCodeSame(expectedCode: 404);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/../../../../../01-responses/RoleUpdateController/404-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function a422IsReturnedOnInvalidJson(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(
            method: 'POST',
            uri: '/role/update',
            content: "invalid-json"
        );

        $this->assertResponseStatusCodeSame(expectedCode: 422);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/../../../../../01-responses/422-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }


}