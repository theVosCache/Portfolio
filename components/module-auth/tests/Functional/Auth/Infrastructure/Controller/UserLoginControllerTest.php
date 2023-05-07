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
        $this->databaseTool->loadFixtures(classNames: [UserFixture::class]);
        $tokenManger = self::bootKernel()->getContainer()->get(id: 'lexik_jwt_authentication.jwt_manager');

        $this->client->request(
            method: "POST",
            uri: '/login',
            content: file_get_contents(
                filename: __DIR__ . "/../../../../02-requests/UserLoginController/200-request.json"
            )
        );

        $this->assertResponseIsSuccessful();
        $responseData = json_decode(
            json: $this->client->getResponse()->getContent(),
            associative: true
        );

        $this->assertSame(expected: ControllerStatusEnum::OK->value, actual: $responseData['Status']);
        $this->assertSame(expected: "Login Successful", actual: $responseData['Message']);

        $tokenData = $tokenManger->parse(jwtToken: $responseData['token']);
        $this->assertSame(expected: 'test@test.nl', actual: $tokenData['username']);
        $this->assertSame(expected: 'Test', actual: $tokenData['firstName']);
        $this->assertSame(expected: 'de Tester', actual: $tokenData['lastName']);
    }
}
