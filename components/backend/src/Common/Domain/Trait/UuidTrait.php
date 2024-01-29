<?php

declare(strict_types=1);

namespace App\Common\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    #[ORM\Column(type: "uuid", unique: true)]
    private string $uuid;

    protected function bootUuidTrait(): void
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
