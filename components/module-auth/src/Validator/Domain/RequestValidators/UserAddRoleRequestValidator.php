<?php

declare(strict_types=1);

namespace App\Validator\Domain\RequestValidators;

use App\Auth\Domain\Exception\RoleNotFoundException;
use App\Auth\Domain\Exception\UserNotFoundException;
use App\Auth\Domain\Repository\RoleRepositoryInterface;
use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Validator\Domain\RequestValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserAddRoleRequestValidator implements RequestValidatorInterface
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    public int $user;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    public int $role;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository
    ) {
    }

    public function setData(array $data): RequestValidatorInterface
    {
        try {
            $user = $this->userRepository->findById($data['user'] ?? 0);
            $this->user = $user->getId();
        } catch (UserNotFoundException $e) {
            // silent
        }

        try {
            $role = $this->roleRepository->findById($data['role'] ?? 0);
            $this->role = $role->getId();
        } catch (RoleNotFoundException $e) {
            // silent
        }

        return $this;
    }

    public function getRequestName(): string
    {
        return 'UserAddRoleRequest';
    }
}
