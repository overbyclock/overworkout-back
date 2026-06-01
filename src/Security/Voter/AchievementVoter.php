<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Achievement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, Achievement>
 */
final class AchievementVoter extends Voter
{
    public const VIEW = 'ACHIEVEMENT_VIEW';
    public const CREATE = 'ACHIEVEMENT_CREATE';
    public const EDIT = 'ACHIEVEMENT_EDIT';
    public const DELETE = 'ACHIEVEMENT_DELETE';
    public const LIST_ALL = 'ACHIEVEMENT_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof Achievement,
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

        if (self::VIEW === $attribute || self::LIST_ALL === $attribute) {
            return true;
        }

        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
