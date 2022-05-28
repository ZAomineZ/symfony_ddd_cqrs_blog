<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Infrastructure\Repository;

use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findSearch(string $q): array
    {
        $query = $this->createQueryBuilder('a');
        return $query
            ->where('LOWER(a.title) LIKE :title')
            ->setParameter('title', '%' . strtolower($q) . '%')
            ->getQuery()
            ->getResult();
    }

    public function save(Article $article): void
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    public function delete(?int $articleID): void
    {
        $this->_em->remove(
            $this->findOneBy(['id' => $articleID])
        );
        $this->_em->flush();
    }

    public function update(Article $article): void
    {
        $this->_em->flush();
    }
}
