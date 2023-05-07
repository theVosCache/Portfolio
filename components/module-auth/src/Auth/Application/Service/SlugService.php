<?php

declare(strict_types=1);

namespace App\Auth\Application\Service;

class SlugService
{
    public function create(string $input): string
    {
        $input = preg_replace(pattern: '/(?<!\ )[A-Z]/', replacement: ' $0', subject: $input);

        $input = trim(string: $input);

        $input = str_replace(search: ' ', replace: '-', subject: $input);

        return strtolower(string: $input);
    }
}
