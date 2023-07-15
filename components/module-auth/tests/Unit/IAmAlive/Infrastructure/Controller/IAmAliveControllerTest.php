<?php

declare(strict_types=1);

namespace App\Tests\Unit\IAmAlive\Infrastructure\Controller;

use App\IAmAlive\Infrastructure\Controller\IAmAliveController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class IAmAliveControllerTest extends TestCase
{
    /** @test */
    public function controllerReturnsCorrectJsonResponseObject(): void
    {
        $controller = new IAmAliveController();

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: 200, actual: $response->getStatusCode());
        $this->assertJson(actualJson: $response->getContent());
    }
}
