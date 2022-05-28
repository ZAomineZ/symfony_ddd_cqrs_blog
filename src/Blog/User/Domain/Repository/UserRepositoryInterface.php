<?php

namespace App\Blog\User\Domain\Repository;

use App\Blog\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function find(int $id);

    public function findByUsername(?string $username = null);

    public function findAll();

    public function save(User $user): void;
}
