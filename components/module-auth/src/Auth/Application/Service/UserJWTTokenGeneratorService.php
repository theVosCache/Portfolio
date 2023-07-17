<?php

declare(strict_types=1);

namespace App\Auth\Application\Service;

use App\Auth\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserJWTTokenGeneratorService
{
    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager
    ) {
    }

    public function generate(User $user, array $additionalClaims = []): string
    {
        $additionalClaims = array_merge([
            'name' => $user->getName()
        ], $additionalClaims);

        return $this->tokenManager->createFromPayload(user: $user, payload: $additionalClaims);
    }
}
