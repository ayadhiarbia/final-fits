<?php
// src/Controller/admin/DashboardController.php

namespace App\Controller\admin;

use App\Entity\User;
use App\Entity\MealPlan;
use App\Entity\Meal;
use App\Entity\Workout;
use App\Entity\Progress;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        // Get statistics for the dashboard
        $stats = $this->getDashboardStatistics();

        // Get chart data
        $chartData = $this->getChartData();

        // Get recent activity
        $recentActivity = $this->getRecentActivity();

        // Render the dashboard template
        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'chartData' => $chartData,
            'recentActivity' => $recentActivity,
        ]);
    }

    private function getDashboardStatistics(): array
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $mealPlanRepo = $this->entityManager->getRepository(MealPlan::class);
        $mealRepo = $this->entityManager->getRepository(Meal::class);
        $workoutRepo = $this->entityManager->getRepository(Workout::class);
        $progressRepo = $this->entityManager->getRepository(Progress::class);

        // Get today's date range for counting new users
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        return [
            'total_users' => $userRepo->count([]),
            'total_meal_plans' => $mealPlanRepo->count([]),
            'total_meals' => $mealRepo->count([]),
            'total_workouts' => $workoutRepo->count([]),
            'total_progress' => $progressRepo->count([]),
            'new_users_today' => $userRepo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $todayStart)
                ->setParameter('end', $todayEnd)
                ->getQuery()
                ->getSingleScalarResult(),
            'avg_user_age' => (float) $userRepo->createQueryBuilder('u')
                ->select('AVG(u.age)')
                ->getQuery()
                ->getSingleScalarResult(),
            // FIXED: Use isBanned instead of isActive
            'active_users' => $userRepo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.isBanned = :banned')
                ->setParameter('banned', false)
                ->getQuery()
                ->getSingleScalarResult(),
        ];
    }

    private function getChartData(): array
    {
        $userRepo = $this->entityManager->getRepository(User::class);

        // Get all users from last 6 months and process in PHP (AVOIDING DQL date functions)
        $sixMonthsAgo = (new \DateTime())->modify('-6 months');

        // Get user data with createdAt
        $recentUsers = $userRepo->createQueryBuilder('u')
            ->select('u.createdAt')
            ->where('u.createdAt >= :sixMonthsAgo')
            ->setParameter('sixMonthsAgo', $sixMonthsAgo)
            ->getQuery()
            ->getResult();

        // Process in PHP to group by month
        $monthlyCounts = [];
        foreach ($recentUsers as $user) {
            if ($user['createdAt'] instanceof \DateTimeInterface) {
                $month = $user['createdAt']->format('Y-m');
                $monthlyCounts[$month] = ($monthlyCounts[$month] ?? 0) + 1;
            }
        }

        // Sort by month
        ksort($monthlyCounts);

        $monthLabels = array_keys($monthlyCounts);
        $monthData = array_values($monthlyCounts);

        // User goals distribution
        $userGoals = $userRepo->createQueryBuilder('u')
            ->select('u.goal, COUNT(u.id) as count')
            ->groupBy('u.goal')
            ->getQuery()
            ->getResult();

        // Prepare data for charts
        $goalLabels = [];
        $goalData = [];
        $goalColors = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        foreach ($userGoals as $index => $goal) {
            $goalLabels[] = $goal['goal'] ?: 'Not Set';
            $goalData[] = $goal['count'];
            $goalColors[] = $colors[$index % count($colors)];
        }

        return [
            'month_labels' => $monthLabels,
            'month_data' => $monthData,
            'goal_labels' => $goalLabels,
            'goal_data' => $goalData,
            'goal_colors' => $goalColors,
        ];
    }

    private function getRecentActivity(): array
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $progressRepo = $this->entityManager->getRepository(Progress::class);

        // Recent users
        $recentUsers = $userRepo->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // Recent progress entries - FIXED: Changed from 'p.date' to 'p.createdAt'
        $recentProgress = $progressRepo->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC') // Changed from 'date' to 'createdAt'
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return [
            'recent_users' => $recentUsers,
            'recent_progress' => $recentProgress,
        ];
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FinalFits admin')
            ->setFaviconPath('images/favicon-admin.png')
            ->setTextDirection('ltr')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-chart-bar');

        // User Management
        yield MenuItem::section('User Management');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Progress Tracking', 'fa fa-chart-line', Progress::class);

        // Nutrition Management
        yield MenuItem::section('Nutrition');
        yield MenuItem::linkToCrud('Meal Plans', 'fa fa-calendar', MealPlan::class);
        yield MenuItem::linkToCrud('Meals', 'fa fa-utensils', Meal::class);

        // Workout Management
        yield MenuItem::section('Fitness');
        yield MenuItem::linkToCrud('Workouts', 'fa fa-dumbbell', Workout::class);

        // System
        yield MenuItem::section('System');
        yield MenuItem::linkToRoute('Back to Site', 'fa fa-globe', 'app_home');
    }
}
