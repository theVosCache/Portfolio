<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Controller;

use App\Auth\Domain\Enums\ControllerStatusEnum;
use App\Tests\DbWebTestCase;
use App\Tests\Fixtures\UserFixture;

class UserLoginControllerTest extends DbWebTestCase
{
    /** @test */
    public function aUserCanSignIn(): void
    {
        $this->databaseTool->loadFixtures([UserFixture::class]);
        $tokenManger = self::bootKernel()->getContainer()->get('lexik_jwt_authentication.jwt_manager');

        $this->client->request(
            method: "POST",
            uri: '/login',
            content: file_get_contents(
                __DIR__ . "/../../../../02-requests/UserLoginController/200-request.json"
            )
        );

        $this->assertResponseIsSuccessful();
        $responseData = json_decode(
            $this->client->getResponse()->getContent(),
            true
        );

        $this->assertSame(ControllerStatusEnum::OK->value, $responseData['Status']);
        $this->assertSame("Login Successful", $responseData['Message']);

        $tokenData = $tokenManger->parse($responseData['token']);
        $this->assertSame('test@test.nl', $tokenData['username']);
        $this->assertSame('Test', $tokenData['firstName']);
        $this->assertSame('de Tester', $tokenData['lastName']);
    }
}
