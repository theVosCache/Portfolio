<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\Domain\RequestValidators;

use App\Validation\Domain\RequestValidators\TestRequestValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TestRequestValidatorTest extends KernelTestCase
{
    /** @test */
    public function aTestRequestCanBeValidated(): void
    {
        $validator = self::bootKernel()->getContainer()->get('test.validator.inner');

        $testRequestValidator = new TestRequestValidator('test');
        $testRequestValidator->setData(['test' => '123456']);

        $errors = $validator->validate($testRequestValidator);

        $this->assertCount(0, $errors);
    }
}