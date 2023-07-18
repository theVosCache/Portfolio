<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity;

use DateTime;

class Role extends AbstractEntity
{
    private int $id;

    private string $name;

    private string $slug;

    public function __construct(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Role
    {
        $this->name = $name;

        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Role
    {
        $this->slug = $slug;

        $this->updatedAt = new DateTime();
        return $this;
    }
}
