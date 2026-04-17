<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, User>
 */
final class UserVoter extends Voter
{
    public const VIEW = 'USER_VIEW';
    public const EDIT = 'USER_EDIT';
    public const DELETE = 'USER_DELETE';
    public const LIST_ALL = 'USER_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof User,
            self::LIST_ALL => null === $subject,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $currentUser),
            self::EDIT => $this->canEdit($subject, $currentUser),
            self::DELETE => $this->canDelete($subject, $currentUser),
            self::LIST_ALL => $this->canListAll($currentUser),
            default => false,
        };
    }

    private function canView(User $targetUser, UserInterface $currentUser): bool
    {
        // Cualquiera puede ver su propio perfil
        // Los admins pueden ver cualquier perfil
        return $this->isAdmin($currentUser) || $this->isSameUser($targetUser, $currentUser);
    }

    private function canEdit(User $targetUser, UserInterface $currentUser): bool
    {
        // Solo admins pueden editar usuarios
        return $this->isAdmin($currentUser);
    }

    private function canDelete(User $targetUser, UserInterface $currentUser): bool
    {
        // Los usuarios pueden borrarse a sí mismos
        // Los admins pueden borrar cualquiera
        return $this->isAdmin($currentUser) || $this->isSameUser($targetUser, $currentUser);
    }

    private function canListAll(UserInterface $currentUser): bool
    {
        // Solo admins pueden listar todos los usuarios
        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    private function isSameUser(User $targetUser, UserInterface $currentUser): bool
    {
        if (!$currentUser instanceof User) {
            return false;
        }

        return $targetUser->getId() === $currentUser->getId();
    }
}
