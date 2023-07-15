<?php

namespace App\Validator\Domain\Enums;

enum RequestStatusEnum: string
{
    case OK = 'ok';
    case ERROR = 'error';
}
