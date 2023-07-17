<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Application\Service;

use App\Auth\Application\Service\UserJWTTokenGeneratorService;
use App\Auth\Domain\Entity\User;
use App\Tests\PrivatePropertyManipulator;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserJWTTokenGeneratorServiceTest extends KernelTestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aTokenIsCorrectlyGenerated(): void
    {
        $tokenManager = self::bootKernel()->getContainer()->get(id: 'test.' . JWTEncoderInterface::class);
        $jwtGeneratorService = self::bootKernel()->getContainer()->get(id: 'test.' . UserJWTTokenGeneratorService::class);
        $user = $this->getTestUser();

        $token = $jwtGeneratorService->generate(user: $user, additionalClaims: []);

        $decodedToken = $tokenManager->decode($token);

        $this->assertArrayHasKey(key: 'iat', array: $decodedToken);
        $this->assertArrayHasKey(key: 'exp', array: $decodedToken);
        $this->assertArrayHasKey(key: 'roles', array: $decodedToken);
        $this->assertArrayHasKey(key: 'username', array: $decodedToken);
        $this->assertArrayHasKey(key: 'name', array: $decodedToken);
    }

    private function getTestUser(): User
    {
        $user = new User(
            name: 'test de tester',
            email: 'test@test.nl',
            password: 'test'
        );

        $this->setByReflection(object: $user, property: 'id', value: 1);

        return $user;
    }
}
