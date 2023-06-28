<?php

namespace App\Validation\Application\Manager;

use App\Validation\Domain\AbstractValidator;
use App\Validation\Domain\Exceptions\MissingValidatorException;

class RequestValidationManager
{
    private array $requestValidators = [];

    public function addValidator(AbstractValidator $validator): void
    {
        $this->requestValidators[$validator->getTestCase()] = $validator;
    }

    /** @throws MissingValidatorException */
    public function getValidatorForRequest(string $testCase): AbstractValidator
    {
        if (!array_key_exists(key: $testCase, array: $this->requestValidators)){
            throw new MissingValidatorException(
                sprintf("Missing Validator for Request: %s", $testCase)
            );
        }

        return $this->requestValidators[$testCase];
    }
}