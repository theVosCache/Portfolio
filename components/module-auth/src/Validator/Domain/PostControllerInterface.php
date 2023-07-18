<?php

declare(strict_types=1);

namespace App\Validator\Domain;

interface PostControllerInterface
{
    /** @throws WrongRequestValidatorException */
    public function setData(RequestValidatorInterface $data);
}
