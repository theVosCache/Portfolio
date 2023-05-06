<?php

namespace App\Auth\Domain\Enums;

enum ControllerStatusEnum: string
{
    case OK = 'OK';
    case ERROR='ERROR';
}
