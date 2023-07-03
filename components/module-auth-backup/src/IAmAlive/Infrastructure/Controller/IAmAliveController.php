<?php

declare(strict_types=1);

namespace App\IAmAlive\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/i-am-alive', name: 'IAmAlive')]
class IAmAliveController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'Status' => 'OK'
        ], JsonResponse::HTTP_OK);
    }
}
