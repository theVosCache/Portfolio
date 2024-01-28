<?php

declare(strict_types=1);

namespace App\IAmAlive\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class IAmAliveController extends AbstractController
{
    public function __construct(
        private readonly int $buildNumber,
        private readonly string $commitHash
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(data: [
            'buildNumber' => $this->buildNumber,
            'commitHash' => $this->commitHash
        ], status: JsonResponse::HTTP_OK);
    }
}