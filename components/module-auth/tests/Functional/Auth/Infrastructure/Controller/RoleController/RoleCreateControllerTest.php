<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller\RoleController;

use App\Tests\DataFixtures\RoleFixture;
use App\Tests\DbWebTestCase;
use ECSPrefix202306\Symfony\Component\HttpFoundation\Response;

class RoleCreateControllerTest extends DbWebTestCase
{
    /** @test */
    public function aRoleCanBeCreated(): void
    {
        $this->databaseTool->loadFixtures(classNames: []);
        $this->client->request(
            method: 'POST',
            uri: '/role/create',
            content: json_encode(value: [
                'type' => 'RoleRequest',
                'data' => [
                    'name' => 'New Role',
                    'slug' => 'new-role'
                ]
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_CREATED);
    }

    /** @test */
    public function aRoleCannotBeCreated(): void
    {
        $this->databaseTool->loadFixtures(classNames: [RoleFixture::class]);
        $this->client->request(
            method: 'POST',
            uri: '/role/create',
            content: json_encode(value: [
                'type' => 'RoleRequest',
                'data' => [
                    'name' => 'Test Role2',
                    'slug' => 'test-role'
                ]
            ])
        );

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_BAD_REQUEST);
    }
}
