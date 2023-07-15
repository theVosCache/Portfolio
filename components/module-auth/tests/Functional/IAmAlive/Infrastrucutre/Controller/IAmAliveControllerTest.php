<?php

declare(strict_types=1);

namespace App\Tests\Functional\IAmAlive\Infrastrucutre\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IAmAliveControllerTest extends WebTestCase
{
    /** @test */
    public function a200JsonResponseIsReturned(): void
    {
        $client = $this->createClient();

        $client->request(method: 'GET', uri: '/i-am-alive');

        $this->assertResponseIsSuccessful();
    }
}
