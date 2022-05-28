<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Application\Model\CreateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateCategoryService implements MessageHandlerInterface
{
    public function __invoke(CreateCategoryCommand $createCategoryCommand): Category
    {
        return Category::create(
            $createCategoryCommand->getName(),
            $createCategoryCommand->getSlug()
        );
    }
}
