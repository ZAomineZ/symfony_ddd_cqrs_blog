<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\DataFixtures;

use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PostFixture extends Fixture
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $categories = [];
        $users = [];

        for ($c = 0; $c < 3; $c++) {
            $category = new Category();
            $category->setName(sprintf('Category #%s', $c));
            $category->setSlug(sprintf('category-%s', $c));
            $categories[] = $category;

            $this->entityManager->persist($category);
        }
        $this->entityManager->flush();

        for ($u = 0; $u < 3; $u++) {
            $user = new User();
            $user->setUsername(sprintf('Username #%s', $u));
            $user->setEmail(sprintf('username#%s@test.com', $u));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'testtes'));
            $users[] = $user;

            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();


        for ($i = 0; $i < 10; $i++) {
            shuffle($categories);
            shuffle($users);

            $post = new Article();
            $post->setTitle(sprintf('Article #%s', $i));
            $post->setSlug(sprintf('Article #%s', $i));
            $post->setBody(sprintf('Je suis le body de "Article #%s"', $i));
            $post->setCategory(current($categories));
            $post->setAuthor(current($users));
            $post->setCreatedAt(new DateTimeImmutable());

            $this->entityManager->persist($post);
        }
        $this->entityManager->flush();
    }
}
