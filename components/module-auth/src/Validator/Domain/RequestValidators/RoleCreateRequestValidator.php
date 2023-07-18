<?php

declare(strict_types=1);

namespace App\Validator\Domain\RequestValidators;

use App\Validator\Domain\RequestValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RoleCreateRequestValidator implements RequestValidatorInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 4)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/\s/', match: false)]
    public string $slug;

    public function setData(array $data): RequestValidatorInterface
    {
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';

        return $this;
    }

    public function getRequestName(): string
    {
        return 'RoleCreateRequest';
    }
}
