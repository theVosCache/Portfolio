<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Application\Manager;

use App\Tests\PrivatePropertyManipulator;
use App\Validator\Application\Manager\RequestValidatorManager;
use App\Validator\Domain\RequestValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidatorManagerTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aRequestValidatorCanBeAdded(): void
    {
        $manager = new RequestValidatorManager(
            validator: $this->createMock(originalClassName: ValidatorInterface::class)
        );

        $this->assertCount(
            expectedCount: 0,
            haystack: $this->getByReflection(object: $manager, property: 'requestValidators')
        );

        $rv = $this->createMock(originalClassName: RequestValidatorInterface::class);
        $manager->addValidator(validator: $rv);

        $this->assertCount(
            expectedCount: 1,
            haystack: $this->getByReflection(object: $manager, property: 'requestValidators')
        );
    }

    /** @test */
    public function aRequestValidatorIsFoundAndValidated(): void
    {
        $manager = new RequestValidatorManager(
            validator: $this->getValidatorInterfaceMock()
        );
        $rv = $this->createMock(originalClassName: RequestValidatorInterface::class);
        $rv->expects($this->once())
            ->method(constraint: 'getRequestName')
            ->willReturn(value: 'test');

        $manager->addValidator(validator: $rv);

        $requestValidator = $manager->validate(requestName: 'test', data: ['test'=>'one']);

        $this->assertInstanceOf(expected: RequestValidatorInterface::class, actual: $requestValidator);
    }

    /** @test */
    public function validateFunctionReturnsFalseOnRequestValidatorNotFound(): void
    {
        $manager = new RequestValidatorManager(
            validator: $this->createMock(ValidatorInterface::class)
        );

        $valid = $manager->validate(requestName: 'test', data: ['test'=>'one']);

        $this->assertFalse(condition: $valid);
    }

    private function getValidatorInterfaceMock(): ValidatorInterface
    {
        $validator = $this->createMock(originalClassName: ValidatorInterface::class);

        $validator->expects($this->once())
            ->method(constraint: 'validate')
            ->willReturn(
                value: $this->createMock(originalClassName: ConstraintViolationListInterface::class)
            );

        return $validator;
    }
}