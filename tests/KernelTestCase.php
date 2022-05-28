<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    protected ObjectManager $em;

    protected UserPasswordHasherInterface|null $passwordHasher;

    protected function setUp(): void
    {
        parent::bootKernel();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = self::getContainer()->get('Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface');

        parent::setUp();
    }

    public function addFixture(string $fixture)
    {
        $loader = new Loader();
        $fixture = str_contains($fixture, 'UserFixture') ||
        str_contains($fixture, 'PostFixture') ?
            new $fixture($this->em, $this->passwordHasher) :
            new $fixture($this->em);
        $loader->addFixture($fixture);

        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
