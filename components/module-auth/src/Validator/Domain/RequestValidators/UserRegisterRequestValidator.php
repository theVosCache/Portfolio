<?php

declare(strict_types=1);

namespace App\Validator\Domain\RequestValidators;

use App\Validator\Domain\RequestValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterRequestValidator implements RequestValidatorInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;

    public function setData(array $data): RequestValidatorInterface
    {
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';

        return $this;
    }

    public function getRequestName(): string
    {
        return 'UserRegisterRequest';
    }
}