<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Application\Model\FindCategoryQuery;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CategoryFinderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function __invoke(FindCategoryQuery $query): ?Category
    {
        $categoryID = $query->getCategoryID();

        return $this->categoryRepository->findOneBy(['id' => $categoryID]);
    }
}
