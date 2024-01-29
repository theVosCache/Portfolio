<?php

declare(strict_types=1);

namespace App\Common\Domain\Trait;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $updatedAt;

    protected function bootTimeStamps(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
