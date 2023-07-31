<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Domain\RequestValidators;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\RequestValidators\UserRoleRequestValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRoleRequestValidatorTest extends KernelTestCase
{
    /** @test */
    public function theCorrectRequestNameIsReturned(): void
    {
        $validator = new UserRoleRequestValidator(
            userRepository: $this->createMock(UserRepositoryInterface::class),
            roleRepository: $this->createMock(RoleRepositoryInterface::class)
        );

        $this->assertSame(
            expected: 'UserAddRoleRequest',
            actual: $validator->getRequestName()
        );
    }

    /**
     * @test
     * @dataProvider exampleRequestDataProvider
     */
    public function aExampleRequestCanBeCorrectlyValidated(
        array $data,
        bool $valid,
        bool $userFound,
        bool $roleFound
    ): void {
        /** @var ValidatorInterface $innerValidator */
        $innerValidator = self::bootKernel()->getContainer()->get('test.validator');

        $validator = new UserRoleRequestValidator(
            userRepository: $this->getUserRepository(userFound: $userFound),
            roleRepository: $this->getRoleRepository(roleFound: $roleFound)
        );

        $validator->setData($data);

        $errors = $innerValidator->validate($validator);
        $errorCheck = count($errors) === 0;

        $this->assertSame(expected: $valid, actual: $errorCheck);
    }

    private function getUserRepository(bool $userFound = true): UserRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: UserRepositoryInterface::class);

        if ($userFound) {
            $userRepository->expects($this->once())
                ->method(constraint: 'findById')
                ->willReturn(value: $this->createMock(User::class));
        } else {
            $userRepository->expects($this->once())
                ->method(constraint: 'findById')
                ->willThrowException(
                    new UserNotFoundException()
                );
        }

        return $userRepository;
    }

    private function getRoleRepository(bool $roleFound = true): RoleRepositoryInterface
    {
        $userRepository = $this->createMock(originalClassName: RoleRepositoryInterface::class);

        if ($roleFound) {
            $userRepository->expects($this->once())
                ->method(constraint: 'findById')
                ->willReturn(value: $this->createMock(Role::class));
        } else {
            $userRepository->expects($this->once())
                ->method(constraint: 'findById')
                ->willThrowException(
                    new RoleNotFoundException()
                );
        }

        return $userRepository;
    }

    public function exampleRequestDataProvider(): array
    {
        return [
            'Correct Request' => [
                'data' => [
                    'user' => 1,
                    'role' => 1
                ],
                'valid' => true,
                'userFound' => true,
                'roleFound' => true
            ],
            'Request but invalid user' => [
                'data' => [
                    'user' => 99,
                    'role' => 1
                ],
                'valid' => false,
                'userFound' => false,
                'roleFound' => true
            ],
            'Request but user is missing' => [
                'data' => [
                    'role' => 1
                ],
                'valid' => false,
                'userFound' => false,
                'roleFound' => true
            ],
            'Request but invalid role' => [
                'data' => [
                    'user' => 1,
                    'role' => 99
                ],
                'valid' => false,
                'userFound' => true,
                'roleFound' => false
            ],
            'Request but role is missing' => [
                'data' => [
                    'user' => 1
                ],
                'valid' => false,
                'userFound' => true,
                'roleFound' => false
            ],
        ];
    }
}
