<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Category\Domain;

use App\Blog\Post\Category\Application\Service\CreateCategoryValidatorService;
use App\Blog\Post\Category\Domain\Entity\Category;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validation;

final class CategoryValidatorTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @dataProvider dataProviderCategoryEntity
     */
    public function testValidatorForCategoryEntity(?string $name, ?string $slug, string $message)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($slug);

        $requestStack = $this->createMock(RequestStack::class);
        $session = $this->createMock(SessionInterface::class);

        $requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($session);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $validatorService = new CreateCategoryValidatorService($validator, $requestStack);
        $errorData = $validatorService($category);

        $this->assertEquals($message, $errorData[0]->getMessage());
    }

    protected function dataProviderCategoryEntity(): Generator
    {
        yield ['name' => null, 'slug' => 'slug', 'message' => 'This value should not be blank.'];

        yield ['name' => 'Test', 'slug' => null, 'message' => 'This value should not be blank.'];

        yield [
            'name' => 'Te',
            'slug' => 'slug',
            'message' => 'This value is too short. It should have 3 characters or more.'
        ];

        yield [
            'name' => 'Test',
            'slug' => 'sl',
            'message' => 'This value is too short. It should have 3 characters or more.'
        ];

        yield [
            'name' => "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.",
            'slug' => 'slug',
            'message' => 'This value is too long. It should have 255 characters or less.'
        ];

        yield [
            'name' => 'Test',
            'slug' => "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.",
            'message' => 'This value is too long. It should have 255 characters or less.'
        ];
    }
}
