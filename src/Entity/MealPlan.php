<?php

namespace App\Entity;

use App\Repository\MealPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealPlanRepository::class)]
class MealPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $totalCalories = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\ManyToOne(inversedBy: 'usersmeal')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Meal>
     */
    #[ORM\OneToMany(targetEntity: Meal::class, mappedBy: 'meals')]
    private Collection $meals;

    /**
     * @var Collection<int, Meal>
     */
    #[ORM\OneToMany(targetEntity: Meal::class, mappedBy: 'meal_plan')]
    private Collection $mealplan;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
        $this->mealplan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTotalCalories(): ?int
    {
        return $this->totalCalories;
    }

    public function setTotalCalories(int $totalCalories): static
    {
        $this->totalCalories = $totalCalories;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): static
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
            $meal->setMeals($this);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): static
    {
        if ($this->meals->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getMeals() === $this) {
                $meal->setMeals(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMealplan(): Collection
    {
        return $this->mealplan;
    }

    public function addMealplan(Meal $mealplan): static
    {
        if (!$this->mealplan->contains($mealplan)) {
            $this->mealplan->add($mealplan);
            $mealplan->setMealplan($this);
        }

        return $this;
    }

    public function removeMealplan(Meal $mealplan): static
    {
        if ($this->mealplan->removeElement($mealplan)) {
            // set the owning side to null (unless already changed)
            if ($mealplan->getMealplan() === $this) {
                $mealplan->setMealplan(null);
            }
        }

        return $this;
    }
}
