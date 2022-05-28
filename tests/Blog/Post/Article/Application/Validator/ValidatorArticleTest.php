<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Validator;

use App\Blog\Post\Article\Application\Model\CreatePostCommand;
use App\Blog\Post\Article\Application\Validator\ValidatorArticle;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use App\Tests\KernelTestCase;
use Generator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validation;

final class ValidatorArticleTest extends KernelTestCase
{
    protected CategoryRepositoryInterface $categoryRepository;

    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->categoryRepository = self::getContainer()->get(CategoryRepositoryInterface::class);
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);
    }

    /**
     * @dataProvider dataProviderArticleEntity
     *
     * @param string|null $title
     * @param string|null $slug
     * @param string|null $body
     * @param int|null $categoryID
     * @param string|null $message
     * @return void
     */
    public function testValidatorForArticleEntity(
        ?string $title,
        ?string $slug,
        ?string $body,
        ?int $categoryID,
        ?string $message
    ): void {
        /** @var User|null $user */
        $user = $this->userRepository->find(1);
        /** @var Category|null $category */
        $category = null !== $categoryID ? $this->categoryRepository->find($categoryID) : null;

        $article = new Article();
        $article->setTitle($title);
        $article->setSlug($slug);
        $article->setBody($body);
        $article->setCategory($category);
        $article->setAuthor($user);

        $requestStack = $this->createMock(RequestStack::class);
        $session = $this->createMock(SessionInterface::class);

        $requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($session);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $validatorArticle = new ValidatorArticle($validator, $requestStack);
        $errorData = $validatorArticle->validatorEntity($article);

        $this->assertEquals($message, $errorData[0]->getMessage());
    }

    public function dataProviderArticleEntity(): Generator
    {
        yield [
            'title' => null,
            'slug' => 'test-slug',
            'body' => 'Je suis un body de test',
            'category' => 1,
            'message' => 'This value should not be blank.'
        ];

        yield [
            'title' => 'Test de test',
            'slug' => null,
            'body' => 'Je suis un body de test',
            'category' => 1,
            'message' => 'This value should not be blank.'
        ];

        yield [
            'title' => 'Test de test',
            'slug' => "test-de-test",
            'body' => 'Je suis un body de test',
            'category' => null,
            'message' => 'This value should not be blank.'
        ];

        yield [
            'title' => 'Test',
            'slug' => "test-de-test",
            'body' => 'Je suis un body de test',
            'category' => 1,
            'message' => 'This value is too short. It should have 5 characters or more.'
        ];

        yield [
            'title' => 'Test de test',
            'slug' => "test",
            'body' => 'Je suis un body de test',
            'category' => 1,
            'message' => 'This value is too short. It should have 5 characters or more.'
        ];

        yield [
            'title' => 'Test de test',
            'slug' => "test-de-test",
            'body' => 'Je test',
            'category' => 1,
            'message' => 'This value is too short. It should have 10 characters or more.'
        ];

        yield [
            'title' => "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.",
            'slug' => "test-de-test",
            'body' => 'Je test',
            'category' => 1,
            'message' => 'This value is too long. It should have 255 characters or less.'
        ];

        yield [
            'title' => 'Test de test',
            'slug' => "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.",
            'body' => 'Je test',
            'category' => 1,
            'message' => 'This value is too long. It should have 255 characters or less.'
        ];
    }
}
