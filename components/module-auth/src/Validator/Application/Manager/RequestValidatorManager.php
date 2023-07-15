<?php

declare(strict_types=1);

namespace App\Validator\Application\Manager;

use App\Validator\Domain\RequestValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidatorManager
{
    /** @var RequestValidatorInterface[] $requestValidators */
    private array $requestValidators = [];

    public function __construct(
        private readonly ValidatorInterface $validator,
        iterable $requestValidators = []
    ) {
        foreach ($requestValidators as $requestValidator) {
            $this->requestValidators[] = $requestValidator;
        }
    }

    public function validate(string $requestName, array $data): RequestValidatorInterface|bool
    {
        foreach ($this->requestValidators as $validator) {
            if ($validator->getRequestName() !== $requestName) {
                continue;
            }

            $validator->setData($data);

            $errors = $this->validator->validate($validator);

            if (count($errors) === 0) {
                return $validator;
            }
        }

        return false;
    }

    public function addValidator(RequestValidatorInterface $validator): void
    {
        $this->requestValidators[] = $validator;
    }
}
