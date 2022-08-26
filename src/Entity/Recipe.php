<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    #[ORM\Column]
    private array $Ingredients = [];

    #[ORM\Column]
    private ?int $PreparationTime = null;

    #[ORM\Column]
    private ?int $CookingTime = null;

    #[ORM\Column]
    private ?int $Difficulty = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getIngredients(): array
    {
        return $this->Ingredients;
    }

    public function setIngredients(array $Ingredients): self
    {
        $this->Ingredients = $Ingredients;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->PreparationTime;
    }

    public function setPreparationTime(int $PreparationTime): self
    {
        $this->PreparationTime = $PreparationTime;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->CookingTime;
    }

    public function setCookingTime(int $CookingTime): self
    {
        $this->CookingTime = $CookingTime;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->Difficulty;
    }

    public function setDifficulty(int $Difficulty): self
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }
}
