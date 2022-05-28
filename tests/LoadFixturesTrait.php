<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

trait LoadFixturesTrait
{
    public static function loadFixtures(array $fixtures, bool $append = false): void
    {
        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();

        $loader = new Loader();
        $purger = new ORMPurger($entityManager);
        $purger->purge();
        $executor = new ORMExecutor($entityManager, $purger);

        foreach ($fixtures as $fixture) {
            $loader->addFixture(
                static::getContainer()->get($fixture)
            );
        }

        $executor->execute($loader->getFixtures(), $append);
    }
}
