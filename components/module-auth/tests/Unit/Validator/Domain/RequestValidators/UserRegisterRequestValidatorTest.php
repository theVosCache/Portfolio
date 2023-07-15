<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Domain\RequestValidators;

use App\Validator\Domain\RequestValidators\UserRegisterRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegisterRequestValidatorTest extends KernelTestCase
{
    /** @test */
    public function theCorrectRequestNameIsReturned(): void
    {
        $validator = new UserRegisterRequestValidator();

        $this->assertSame(
            expected: 'UserRegisterRequest',
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

        $validator = new UserRegisterRequestValidator();

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
                    'name' => 'Test de Tester',
                    'email' => 'test@test.nl',
                    'password' => 'testtest'
                ],
                'valid' => true
            ],
            'Request but password to short' => [
                'data' => [
                    'name' => 'Test de Tester',
                    'email' => 'test@test.nl',
                    'password' => 'test'
                ],
                'valid' => false
            ],
            'Request but password is missing' => [
                'data' => [
                    'name' => 'Test de Tester',
                    'email' => 'test@test.nl'
                ],
                'valid' => false
            ],
            'Request but email is incorrect' => [
                'data' => [
                    'name' => 'Test de Tester',
                    'email' => 'dit is geen email',
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
            'Request but email is missing' => [
                'data' => [
                    'name' => 'Test de Tester',
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
            'Request but name is to short' => [
                'data' => [
                    'name' => 'Test',
                    'email' => 'test@test.nl',
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
            'Request but name is missing' => [
                'data' => [
                    'email' => 'test@test.nl',
                    'password' => 'testtest'
                ],
                'valid' => false
            ],
        ];
    }
}
