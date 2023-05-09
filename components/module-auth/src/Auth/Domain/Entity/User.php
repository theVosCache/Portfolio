<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity;

use App\Auth\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'first_name', type: 'string')]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string')]
    private string $lastName;

    #[ORM\Column(name: 'email', type: 'string')]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string')]
    private string $password;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_role')]
    private Collection $roles;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;

        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getRolesRelations(): ArrayCollection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        $this->roles->add($role);

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->remove(
            key: $this->roles->indexOf(element: $role)
        );

        return $this;
    }

    public function hasRole(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    public function getRoles(): array
    {
        $roles = [
            'ROLE_USER'
        ];

        /** @var Role $role */
        foreach ($this->roles as $role) {
            $roles[] = $role->getSlug();
        }

        return $roles;
    }

    /** @codeCoverageIgnore */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
