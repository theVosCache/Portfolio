<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Domain\RequestValidators;

use App\Validator\Domain\RequestValidators\RoleRequestValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoleRequestValidatorTest extends KernelTestCase
{
    /** @test */
    public function theCorrectRequestNameIsReturned(): void
    {
        $validator = new RoleRequestValidator();

        $this->assertSame(
            expected: 'RoleRequest',
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
        $innerValidator = self::bootKernel()->getContainer()->get(id: 'test.validator');

        $validator = new RoleRequestValidator();
        $validator->setData(data: $data);

        $errors = $innerValidator->validate($validator);

        $this->assertSame(expected: $valid, actual: (count($errors) === 0));
    }

    public function exampleRequestDataProvider(): array
    {
        return [
            'Correct Request' => [
                'data' => [
                    'name' => 'New Role',
                    'slug' => 'new-role'
                ],
                'valid' => true
            ],
            'Request With Missing Name' => [
                'data' => [
                    'slug' => 'new-role'
                ],
                'valid' => false
            ],
            'Request With Name That Is Not Long Enough' => [
                'data' => [
                    'name' => 'usr',
                    'slug' => 'user'
                ],
                'valid' => false
            ],
            'Request With Missing Slug' => [
                'data' => [
                    'name' => 'Missing Slug'
                ],
                'valid' => false
            ],
            'Request With Spaces In The Slug' => [
                'data' => [
                    'name' => 'New Role',
                    'slug' => 'new role'
                ],
                'valid' => false
            ]
        ];
    }
}
