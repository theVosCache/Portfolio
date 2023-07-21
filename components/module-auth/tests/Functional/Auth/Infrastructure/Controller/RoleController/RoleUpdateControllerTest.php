<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\RoleController;

use App\Tests\DataFixtures\RoleFixture;
use App\Tests\DbWebTestCase;
use ECSPrefix202306\Symfony\Component\HttpFoundation\Response;

class RoleUpdateControllerTest extends DbWebTestCase
{
    /** @test */
    public function aRoleCanBeCreated(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        $this->client->request(
            method: 'POST',
            uri: '/role/update/1',
            content: json_encode(value: [
                'type' => 'RoleCreateRequest',
                'data' => [
                    'name' => 'New Role',
                    'slug' => 'new-role'
                ]
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_NO_CONTENT);
    }
}
