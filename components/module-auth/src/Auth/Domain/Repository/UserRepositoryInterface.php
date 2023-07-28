<?php

declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Exception\UserNotFoundException;

interface UserRepositoryInterface
{
    /** @throws UserNotFoundException */
    public function findById(int $id): User;

    /** @throws UserNotFoundException */
    public function findByEmail(string $email): User;
}
