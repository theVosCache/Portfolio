<?php

declare(strict_types=1);

namespace App\Auth\Application\Service;

class SlugService
{
    public function create(string $input): string
    {
        $input = preg_replace('/(?<!\ )[A-Z]/', ' $0', $input);

        $input = trim($input);

        $input = str_replace(' ', '-', $input);

        return strtolower($input);
    }
}
