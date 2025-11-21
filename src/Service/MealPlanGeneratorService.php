<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Meal;
use App\Entity\MealPlan;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;

class MealPlanGeneratorService
{
    private const TDEE_MULTIPLIER = [
        'lose_weight' => 0.8, // 20% deficit
        'maintain' => 1.0,
        'gain_muscle' => 1.15, // 15% surplus
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MealRepository $mealRepository
    ) {}

    /**
     * Calculates the Basal Metabolic Rate (BMR) using the Mifflin-St Jeor equation.
     */
    public function calculateBMR(User $user): float
    {
        // Mifflin-St Jeor Equation:
        // Men: (10 * weight_kg) + (6.25 * height_cm) - (5 * age_years) + 5
        // Women: (10 * weight_kg) + (6.25 * height_cm) - (5 * age_years) - 161
        // Since we don't have gender, we'll use the male formula as a general estimate.
        // In a real app, gender would be a required field.
        return (10 * $user->getWeight()) + (6.25 * $user->getHeight()) - (5 * $user->getAge()) + 5;
    }

    /**
     * Calculates the Total Daily Energy Expenditure (TDEE) based on BMR and a general activity multiplier (1.55 for moderately active).
     */
    public function calculateTDEE(User $user): float
    {
        // Assuming a general activity level (e.g., Moderately Active: 1.55)
        $activityMultiplier = 1.55;
        return $this->calculateBMR($user) * $activityMultiplier;
    }

    /**
     * Calculates the target daily calorie intake based on TDEE and user goal.
     */
    public function calculateTargetCalories(User $user): int
    {
        $tdee = $this->calculateTDEE($user);
        $goal = strtolower(str_replace(' ', '_', $user->getGoal()));

        $multiplier = self::TDEE_MULTIPLIER[$goal] ?? self::TDEE_MULTIPLIER['maintain'];

        return (int) round($tdee * $multiplier);
    }

    /**
     * Generates a simple, sample meal plan for a single day.
     * In a real application, this would involve complex logic and a large meal database.
     */
    public function generateMealPlan(User $user, string $day): MealPlan
    {
        $targetCalories = $this->calculateTargetCalories($user);

        // Fetch all meals (for simplicity, in a real app, you'd filter by criteria)
        $allMeals = $this->mealRepository->findAll();

        // Simple logic: pick a few meals that roughly meet the target calories
        $selectedMeals = [];
        $currentCalories = 0;

        // Shuffle meals to get a different plan each time
        shuffle($allMeals);

        foreach ($allMeals as $meal) {
            if ($currentCalories + $meal->getCalories() <= $targetCalories * 1.1) { // Allow 10% over
                $selectedMeals[] = $meal;
                $currentCalories += $meal->getCalories();
            }
            if ($currentCalories >= $targetCalories * 0.9) { // Stop if close to target
                break;
            }
        }

        $mealPlan = new MealPlan();
        $mealPlan->setUser($user);
        $mealPlan->setTitle(sprintf('%s Meal Plan for %s', $user->getName(), $day));
        $mealPlan->setDay($day);
        $mealPlan->setTotalCalories($currentCalories);

        foreach ($selectedMeals as $meal) {
            $mealPlan->addMeal($meal);
        }

        $this->entityManager->persist($mealPlan);
        $this->entityManager->flush();

        return $mealPlan;
    }
}
