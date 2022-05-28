<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use Symfony\Component\Validator\Constraints as Assert;

class Category extends AggregateRoot
{
    private int $id;

    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255)
    ]
    private ?string $name;

    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255)
    ]
    private ?string $slug;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name = null): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug = null): void
    {
        $this->slug = $slug;
    }

    public static function create(?string $name = null, ?string $slug = null): self
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($slug);

        return $category;
    }
}
