<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Domain\Enums\ControllerStatusEnum;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractBaseController
{
    /** @codeCoverageIgnore  */
    public function getDataFromRequest(Request $request): array|JsonResponse
    {
        if ($request->isMethod('POST')) {
            try {
                return json_decode(json: $request->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                return new JsonResponse(
                    data: [
                        'Status' => ControllerStatusEnum::ERROR,
                        'Message' => $e->getMessage()
                    ],
                    status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        return [];
    }

    public function buildErrorResponse(string $message, array $data = [], int $statusCode = 400): JsonResponse
    {
        return new JsonResponse(
            data: [
                'Status' => ControllerStatusEnum::ERROR,
                'Message' => $message,
                ...$data
            ],
            status: $statusCode
        );
    }
    public function buildSuccessResponse(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return new JsonResponse(
            data: [
                'Status' => ControllerStatusEnum::OK,
                'Message' => $message,
                ...$data
            ],
            status: $statusCode
        );
    }
}