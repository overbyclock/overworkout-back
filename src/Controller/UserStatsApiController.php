<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserStatsApiController extends AbstractController
{
    public function __construct(
        private readonly UserStatsService $userStatsService,
    ) {
    }

    #[Route('/user/stats', name: 'user_stats_dashboard', methods: ['GET'])]
    public function getDashboardStats(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($this->userStatsService->getDashboardStats($user));
    }
}
