<?php

declare(strict_types=1);

namespace App\Tests\Unit\IAmAlive\Infrastructure\Controller;

use App\IAmAlive\Infrastructure\Controller\IAmAliveController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class IAmAliveControllerTest extends TestCase
{
    /** @test */
    public function aHttpOkResponseIsReturned(): void
    {
        $controller = new IAmAliveController(
            buildNumber: 1,
            commitHash: '123abc'
        );

        $response = $controller();

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_OK, actual: $response->getStatusCode());

        $data = json_decode(json: $response->getContent(), associative: true);
        $this->assertSame(expected: 1, actual: $data['buildNumber']);
        $this->assertSame(expected: '123abc', actual: $data['commitHash']);
    }
}
