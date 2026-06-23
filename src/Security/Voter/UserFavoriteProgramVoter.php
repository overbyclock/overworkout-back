<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, UserFavoriteProgram>
 */
final class UserFavoriteProgramVoter extends Voter
{
    public const MANAGE = 'USER_FAVORITE_PROGRAM_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::MANAGE === $attribute && $subject instanceof UserFavoriteProgram;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $this->isOwner($subject, $currentUser);
    }

    private function isOwner(UserFavoriteProgram $favorite, UserInterface $currentUser): bool
    {
        if (!$currentUser instanceof User) {
            return false;
        }

        $owner = $favorite->getUser();

        if (null === $owner) {
            return false;
        }

        $ownerId = $owner->getId();
        $currentUserId = $currentUser->getId();

        if (null !== $ownerId && null !== $currentUserId) {
            return $ownerId === $currentUserId;
        }

        return $owner === $currentUser;
    }
}
