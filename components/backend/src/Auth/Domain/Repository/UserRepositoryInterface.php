<?php

declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Domain\Entity\User;

interface UserRepositoryInterface
{
    //    public function findByUuid(string $uuid): ?User;
    public function findByEmail(string $email): ?User;
}
