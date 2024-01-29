<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity;

use App\Auth\Domain\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Repository\MariaDbUserRepository;
use App\Common\Domain\Entity\AbstractEntity;
use App\Common\Domain\Trait\Timestamps;
use App\Common\Domain\Trait\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MariaDbUserRepository::class)]
class User extends AbstractEntity
{
    use Uuid, Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;
    #[ORM\Column(type: 'string', unique: true)]
    private string $email;
    #[ORM\Column(type: 'string')]
    private string $password;

    public function __construct(string $name, string $email, string $password)
    {
        parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }
}
