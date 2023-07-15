<?php

declare(strict_types=1);

namespace App\Validator\Domain;

interface PostControllerInterface
{
    public function setData(RequestValidatorInterface $data);
}
