<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Auth\Domain\Entity\Role;
use App\Tests\PrivatePropertyManipulator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    use PrivatePropertyManipulator;


    public function load(ObjectManager $manager): void
    {
        $role = new Role(
            name: 'Test Role',
            slug: 'test-role'
        );
        $this->setByReflection(object: $role, property: 'id', value: 1);

        $manager->persist($role);
        $manager->flush();
    }
}
