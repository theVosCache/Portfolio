<?php

declare(strict_types=1);

namespace App\Tests\Functional\IAmAlive\Infrastructure\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class IAmAliveControllerTest extends WebTestCase
{
    /** @test */
    public function aJsonResponseIsReturned(): void
    {
        $client = self::createClient();

        $client->request(method: 'GET', uri: '/i-am-alive');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../../../../01-responses/IAmAliveController/200-response.json',
            $client->getResponse()->getContent()
        );
    }
}
