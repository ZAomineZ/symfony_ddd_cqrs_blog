<?php

declare(strict_types=1);

namespace App\Blog\User\Infrastructure\Repository;

use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByUsername(?string $username = null): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameters(['username' => $username])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
