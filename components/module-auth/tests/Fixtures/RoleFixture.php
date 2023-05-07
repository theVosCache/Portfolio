<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Auth\Application\Service\SlugService;
use App\Auth\Domain\Entity\Role;
use App\Tests\PrivatePropertyManipulator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    use PrivatePropertyManipulator;

    public function __construct(
        private readonly SlugService $slugService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $role = new Role(
            name: 'Test Role',
            slug: $this->slugService->create('Test Role')
        );

        $this->setByReflection($role, 'id', 1);

        $manager->persist($role);
        $manager->flush();
    }
}
