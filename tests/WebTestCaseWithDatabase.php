<?php

declare(strict_types=1);

namespace App\Tests;

use App\Blog\User\Domain\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\Persistence\ObjectManager;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

final class WebTestCaseWithDatabase extends WebTestCase
{
    protected KernelBrowser $client;

    private ObjectManager $em;

    private UserPasswordHasher|null $passwordHasher;

    /**
     * @throws ToolsException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = WebTestCaseWithDatabase::createClient();

        // Make sure we are in the test environment
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Tests cases with fresh database must be executed in the test environment');
        }

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = self::getContainer()->get('Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface');

        $this->metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($this->em);
        $this->schemaTool->dropSchema($this->metaData);
        $this->schemaTool->createSchema($this->metaData);
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

    public function login(?User $user)
    {
        if (null === $user) {
            return;
        }

        $this->client->loginUser($user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(2);
        $purger->purge();
    }
}
