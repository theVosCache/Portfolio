<?php

declare(strict_types=1);

namespace App\Tests\Functional\IAmAlive\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IAmAliveControllerTest extends WebTestCase
{
    /** @test */
    public function aHttpOkResponseIsReturned(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/i-am-alive');

        $this->assertResponseIsSuccessful();
    }
}
