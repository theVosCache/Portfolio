<?php

namespace App\Validation\Domain\RequestValidators;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\Domain\AbstractValidator;

class TestRequestValidator extends AbstractValidator
{
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 6)]
    public string $test;

    public function __construct(string $testCase)
    {
        parent::__construct($testCase);
    }

    public function setData(array $data): void
    {
        $this->test = $data['test'];
    }
}