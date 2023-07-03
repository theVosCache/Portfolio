<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\Application\Manager;

use App\Tests\PrivatePropertyManipulator;
use App\Validation\Application\Manager\RequestValidationManager;
use App\Validation\Domain\AbstractValidator;
use App\Validation\Domain\Exceptions\MissingValidatorException;
use PHPUnit\Framework\TestCase;

class RequestValidationManagerTest extends TestCase
{
    use PrivatePropertyManipulator;

    /** @test */
    public function aValidatorCanBeAddedToTheManager(): void
    {
        $manager = new RequestValidationManager();

        $testCount1 = $this->getByReflection(object: $manager, property: 'requestValidators');
        $this->assertCount(expectedCount: 0, haystack: $testCount1);

        $manager->addValidator($this->createMock(AbstractValidator::class));

        $testCount2 = $this->getByReflection(object: $manager, property: 'requestValidators');
        $this->assertCount(expectedCount: 1, haystack: $testCount2);
    }

    /** @test */
    public function aValidatorCanBeSelectedViaRequestClassName(): void
    {
        $manager = new RequestValidationManager();

        $manager->addValidator($this->getAbstractValidatorMock());

        $testCount1 = $this->getByReflection(object: $manager, property: 'requestValidators');
        $this->assertCount(expectedCount: 1, haystack: $testCount1);

        $validator = $manager->getValidatorForRequest(testCase: 'test');
        $this->assertInstanceOf(AbstractValidator::class, $validator);
    }

    /** @test */
    public function aMissingValidatorExceptionIsThrownWhenValidatorCouldNotBeRetrieved(): void
    {
        $this->expectException(MissingValidatorException::class);

        $manager = new RequestValidationManager();

        $manager->getValidatorForRequest(testCase: 'test');
    }

    private function getAbstractValidatorMock(): AbstractValidator
    {
        $validator = $this->createMock(originalClassName: AbstractValidator::class);

        $validator->expects($this->once())
            ->method(constraint: 'getTestCase')
            ->willReturn(value: 'test');

        return $validator;
    }
}