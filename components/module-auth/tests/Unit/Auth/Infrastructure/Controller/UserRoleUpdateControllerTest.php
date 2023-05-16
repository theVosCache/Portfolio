<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Infrastructure\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRoleUpdateControllerTest extends TestCase
{
    /** @test */
    public function aUserHasHisRolesUpdated(): void
    {
        $controller = new UserRoleUpdateController(
            entityManager: $this->getEntityManagerMock()
        );

        $response = $controller(request: $this->getRequestMock());

        $this->assertInstanceOf(expected: JsonResponse::class, actual: $response);
        $this->assertSame(expected: JsonResponse::HTTP_OK, actual: $response->getStatusCode());
    }

    private function getRequestMock(bool $invalid = false): Request
    {
        $request = $this->createMock(originalClassName: Request::class);

        $request->expects($this->once())
            ->method(constraint: 'isMethod')
            ->with('POST')
            ->willReturn(value: true);

        if ($invalid) {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(value: "invalid-json");
        } else {
            $request->expects($this->once())
                ->method(constraint: 'getContent')
                ->willReturn(
                    value: json_encode(value: [
                        'userIdentifier' => 'test@test.nl',
                        'roles' => ['test-role']
                    ])
                );
        }

        return $request;
    }
}
