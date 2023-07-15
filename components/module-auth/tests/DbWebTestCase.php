<?php

declare(strict_types=1);

namespace App\Tests;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DbWebTestCase extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createClient();
        /** @var AbstractDatabaseTool $databaseTool */
        $databaseTool = static::getContainer()->get(id: DatabaseToolCollection::class)->get();
        $this->databaseTool = $databaseTool;
    }
}
