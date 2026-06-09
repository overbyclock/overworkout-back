<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserBenchmarkAttempt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class BenchmarkAttemptVoter extends Voter
{
    public const VIEW = 'BENCHMARK_ATTEMPT_VIEW';
    public const EDIT = 'BENCHMARK_ATTEMPT_EDIT';
    public const DELETE = 'BENCHMARK_ATTEMPT_DELETE';
    public const LIST_OWN = 'BENCHMARK_ATTEMPT_LIST_OWN';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::LIST_OWN], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (self::LIST_OWN === $attribute) {
            return true;
        }

        // Para VIEW, EDIT, DELETE el subject es el ID del attempt (int)
        if (!\is_int($subject)) {
            return false;
        }

        $attempt = $this->entityManager->getRepository(UserBenchmarkAttempt::class)->find($subject);

        if (null === $attempt) {
            return false;
        }

        $attemptUser = $attempt->getUser();

        if (null === $attemptUser) {
            return false;
        }

        // Solo el dueño puede ver/editar/eliminar
        if ($attemptUser->getId() !== $user->getId()) {
            return false;
        }

        if (self::DELETE === $attribute) {
            // Solo se pueden eliminar intentos en progreso
            return UserBenchmarkAttempt::STATUS_IN_PROGRESS === $attempt->getStatus();
        }

        return true;
    }
}
