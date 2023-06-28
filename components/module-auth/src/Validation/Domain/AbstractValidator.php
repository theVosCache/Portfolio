<?php

declare(strict_types=1);

namespace App\Validation\Domain;

abstract class AbstractValidator
{
    public function __construct(
        protected string $testCase
    ) {
    }

    public function getTestCase(): string
    {
        return $this->testCase;
    }
}