<?php

declare(strict_types=1);

namespace App\Tests;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbKernelTestCase extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var AbstractDatabaseTool $databaseTool */
        $databaseTool = static::getContainer()->get(id: DatabaseToolCollection::class)->get();

        $this->databaseTool = $databaseTool;
    }
}
