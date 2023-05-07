<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Enums;

use App\Auth\Domain\Enums\ControllerStatusEnum;
use PHPUnit\Framework\TestCase;

class ControllerStatusEnumTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function enumValuesAreReturnedCorrectly(ControllerStatusEnum $statusEnum, string $value): void
    {
        $this->assertSame(expected: $statusEnum->value, actual: $value);
    }

    public static function dataProvider(): array
    {
        return [
            'Status OK' => [
                'statusEnum' => ControllerStatusEnum::OK,
                'value' => 'OK'
            ],
            'Status Error' => [
                'statusEnum' => ControllerStatusEnum::ERROR,
                'value' => 'ERROR'
            ]
        ];
    }
}
