<?php

namespace App\Tests\Unit\Validator\Domain\Enums;

use App\Validator\Domain\Enums\RequestStatusEnum;
use PHPUnit\Framework\TestCase;

class RequestStatusEnumTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function enumValuesAreCorrect(
        RequestStatusEnum $enumValue,
        string $expectedValue
    ): void
    {
        $this->assertSame(expected: $expectedValue, actual: $enumValue->value);
    }

    public function dataProvider(): array
    {
        return [
            'Request OK' => [
                'enumValue' => RequestStatusEnum::OK,
                'expectedValue' => 'ok'
            ],
            'Request ERROR' => [
                'enumValue' => RequestStatusEnum::ERROR,
                'expectedValue' => 'error'
            ]
        ];
    }
}
