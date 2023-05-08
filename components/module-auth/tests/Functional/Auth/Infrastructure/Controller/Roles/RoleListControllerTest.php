<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\Roles;

use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\RoleFixture;

class RoleListControllerTest extends DbWebTestCase
{
    /** @test */
    public function allRolesCanBeListed(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        $this->client->request(method: "GET", uri: "/roles");

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__ . "/../../../../../01-responses/RoleListController/200-response.json",
            actualJson: $this->client->getResponse()->getContent()
        );
    }
}
