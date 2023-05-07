<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\Service;

use App\Auth\Application\Service\SlugService;
use PHPUnit\Framework\TestCase;

class SlugServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function aStringCanBeConvertedToASlug(string $input, string $expectedSlug): void
    {
        $slugService = new SlugService();

        $this->assertSame($expectedSlug, $slugService->create($input));
    }

    public static function dataProvider(): array
    {
        return [
            'CamelCase' => [
                'input' => 'ThisNeedToBecomeASlug',
                'expectedSlug' => 'this-need-to-become-a-slug'
            ],
            'pascalCase' => [
                'input' => 'thisIsAlsoASlug',
                'expectedSlug' => 'this-is-also-a-slug',
            ],
            'title' => [
                'input' => 'This is a Title',
                'expectedSlug' => 'this-is-a-title'
            ]
        ];
    }
}
