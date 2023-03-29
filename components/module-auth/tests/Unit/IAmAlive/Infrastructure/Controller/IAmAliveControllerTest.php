<?php

declare(strict_types=1);

namespace App\Tests\Unit\IAmAlive\Infrastructure\Controller;

use App\IAmAlive\Infrastructure\Controller\IAmAliveController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class IAmAliveControllerTest extends TestCase
{
    /** @test */
    public function aJsonResponseIsReturned(): void
    {
        $controller = new IAmAliveController();

        $response = $controller();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../../../../01-responses/IAmAliveController/200-response.json',
            $response->getContent()
        );
    }
}
