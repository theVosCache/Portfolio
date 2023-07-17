<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Domain\RequestValidators;

use App\Validator\Domain\RequestValidators\UserLoginRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserLoginRequestValidatorTest extends KernelTestCase
{
    /** @test */
    public function theCorrectRequestNameIsReturned(): void
    {
        $validator = new UserLoginRequestValidator();

        $this->assertSame(
            expected: 'UserLoginRequest',
            actual: $validator->getRequestName()
        );
    }

    /**
     * @test
     * @dataProvider exampleRequestDataProvider
     */
    public function aExampleRequestCanBeCorrectlyValidated(array $data, bool $valid): void
    {
        /** @var ValidatorInterface $innerValidator */
        $innerValidator = self::bootKernel()->getContainer()->get('test.validator');

        $validator = new UserLoginRequestValidator();

        $validator->setData($data);

        $errors = $innerValidator->validate($validator);
        $errorCheck = count($errors) === 0;

        $this->assertSame(expected: $valid, actual: $errorCheck);
    }

    public function exampleRequestDataProvider(): array
    {
        return [
            'Correct Request' => [
                'data' => [
                    'email' => 'test@test.nl',
                    'password' => 'testtest'
                ],
                'valid' => true
            ],
            'Request but password to short' => [
                'data' => [
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ],
                'valid' => false
            ],
            'Request but password is missing' => [
                'data' => [
                    'email' => 'test@test.nl'
                ],
                'valid' => false
            ],
            'Request but email is incorrect' => [
                'data' => [
                    'email' => 'dit is geen email',
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
            'Request but email is missing' => [
                'data' => [
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
        ];
    }
}
