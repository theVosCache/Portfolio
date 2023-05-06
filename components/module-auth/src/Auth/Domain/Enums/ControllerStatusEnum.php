<?php

declare(strict_types=1);

namespace App\Auth\Domain\Enums;

enum ControllerStatusEnum: string
{
    case OK = 'OK';
    case ERROR = 'ERROR';
}
