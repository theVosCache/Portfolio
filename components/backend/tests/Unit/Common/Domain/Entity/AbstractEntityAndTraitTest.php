<?php

declare(strict_types=1);

namespace App\Tests\Unit\Common\Domain\Entity;

use App\Common\Domain\Entity\AbstractEntity;
use App\Common\Domain\Trait\Timestamps;
use App\Common\Domain\Trait\Uuid;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class AbstractEntityAndTraitTest extends TestCase
{
    /** @test */
    public function aUuidTraitCanBeBooted(): void
    {
        $testObject = new class() extends AbstractEntity {
            use Uuid;
        };

        $this->assertNotEmpty(actual: $testObject->getUuid());
    }

    public function aTimestampsTraitCanBeBooted(): void
    {
        $testObject = new class() extends AbstractEntity {
            use Timestamps;
        };

        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $testObject->getCreatedAt());
        $this->assertInstanceOf(expected: DateTimeInterface::class, actual: $testObject->getUpdatedAt());

        $updatedAt = $testObject->getUpdatedAt();
        $testObject->setUpdatedAt(updatedAt: new DateTime());

        $this->assertNotSame(expected: $updatedAt, actual: $testObject->getUpdatedAt());
    }
}
