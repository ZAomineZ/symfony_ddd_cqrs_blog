<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\DataFixtures;

use App\Blog\Post\Category\Domain\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixture extends Fixture
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new Category();
            $user->setName(sprintf('Test #%s', $i));
            $user->setSlug(sprintf('test-%s', $i));

            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
    }
}
