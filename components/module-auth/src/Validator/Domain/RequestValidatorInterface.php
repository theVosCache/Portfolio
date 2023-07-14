<?php

declare(strict_types=1);

namespace App\Validator\Domain;

interface RequestValidatorInterface
{
    public function setData(array $data): self;

    public function getRequestName(): string;
}