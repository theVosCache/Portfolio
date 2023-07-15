<?php

declare(strict_types=1);

namespace App\IAmAlive\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IAmAliveController
{
    #[Route(path: '/i-am-alive', name: 'IAmAlive')]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            data: [
                'status' => 'I Am Alive'
            ],
            status: JsonResponse::HTTP_OK
        );
    }
}
