<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TrainingLevel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, TrainingLevel>
 */
final class TrainingLevelVoter extends Voter
{
    public const VIEW = 'LEVEL_VIEW';
    public const CREATE = 'LEVEL_CREATE';
    public const EDIT = 'LEVEL_EDIT';
    public const DELETE = 'LEVEL_DELETE';
    public const LIST_ALL = 'LEVEL_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof TrainingLevel,
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

        // Los niveles son públicos para lectura
        if (self::VIEW === $attribute || self::LIST_ALL === $attribute) {
            return true;
        }

        // Solo admins pueden crear, editar y borrar niveles
        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
