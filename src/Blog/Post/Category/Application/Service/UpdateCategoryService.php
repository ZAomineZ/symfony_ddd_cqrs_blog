<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Application\Model\UpdateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateCategoryService implements MessageHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly ValidatorInterface $validator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function __invoke(UpdateCategoryCommand $updateCategoryCommand): array
    {
        /** @var Category|null $category */
        $category = $this->categoryRepository->findOneBy(['id' => $updateCategoryCommand->getId()]);

        $category->setName($updateCategoryCommand->getName());
        $category->setSlug($updateCategoryCommand->getSlug());

        $violations = $this->validatorEntity($category);

        return [$category, $violations];
    }

    protected function validatorEntity(Category $category): array
    {
        $errorsData = [];

        foreach ($this->validator->validate($category) as $error) {
            $errorsData[] = $error;
        }

        $session = $this->requestStack->getSession();

        $session->set('errorsValidation', $this->validator->validate($category));

        return $errorsData;
    }
}
