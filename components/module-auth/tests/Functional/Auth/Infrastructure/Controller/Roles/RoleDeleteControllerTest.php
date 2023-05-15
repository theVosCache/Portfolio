<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\Roles;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\RoleFixture;

class RoleDeleteControllerTest extends DbWebTestCase
{
    /** @test */
    public function aRoleCanBeDeleted(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);

        $this->client->request(
            method: "POST",
            uri: "/role/delete",
            content: file_get_contents(__DIR__ . '/Requests/role-delete-request.json')
        );

        $this->assertResponseIsSuccessful();
    }

    /** @test */
    public function aHTTP400IsReturnedWhenRoleNotFound(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(
            method: "POST",
            uri: "/role/delete",
            content: file_get_contents(__DIR__ . '/Requests/role-delete-request.json')
        );

        $this->assertResponseStatusCodeSame(expectedCode: 400);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/Responses/RoleDeleteController/400-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }

    /** @test */
    public function aHTTP422IsReturnedOnInvalidJson(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);

        $this->client->request(
            method: "POST",
            uri: "/role/delete",
            content: "invalid-json"
        );

        $this->assertResponseStatusCodeSame(expectedCode: 422);
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . '/Responses/422-response.json',
            actualJson: $this->client->getResponse()->getContent()
        );
    }
}
