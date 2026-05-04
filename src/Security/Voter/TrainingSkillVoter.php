<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TrainingSkill;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, TrainingSkill>
 */
final class TrainingSkillVoter extends Voter
{
    public const VIEW = 'TRAINING_SKILL_VIEW';
    public const CREATE = 'TRAINING_SKILL_CREATE';
    public const EDIT = 'TRAINING_SKILL_EDIT';
    public const DELETE = 'TRAINING_SKILL_DELETE';
    public const LIST_ALL = 'TRAINING_SKILL_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof TrainingSkill,
            self::CREATE, self::LIST_ALL => null === $subject,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        // Todos los skills son públicos para lectura
        if (self::VIEW === $attribute || self::LIST_ALL === $attribute) {
            return true;
        }

        // Solo admins pueden crear, editar y borrar
        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
