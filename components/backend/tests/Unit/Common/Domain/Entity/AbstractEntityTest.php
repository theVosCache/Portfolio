<?php

declare(strict_types=1);

namespace App\Tests\Unit\Common\Domain\Entity;

use App\Common\Domain\Entity\AbstractEntity;
use App\Common\Domain\Trait\UuidTrait;
use PHPUnit\Framework\TestCase;

class AbstractEntityTest extends TestCase
{
    /** @test */
    public function aUuidTraitCanBeBooted(): void
    {
        $testObject = new class() extends AbstractEntity {
            use UuidTrait;
        };

        $this->assertNotEmpty(actual: $testObject->getUuid());
    }
}
