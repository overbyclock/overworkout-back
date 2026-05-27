<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TrainingWeekInfo;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, TrainingWeekInfo>
 */
final class TrainingWeekInfoVoter extends Voter
{
    public const VIEW = 'WEEK_INFO_VIEW';
    public const CREATE = 'WEEK_INFO_CREATE';
    public const EDIT = 'WEEK_INFO_EDIT';
    public const DELETE = 'WEEK_INFO_DELETE';
    public const LIST_ALL = 'WEEK_INFO_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof TrainingWeekInfo,
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

        // La información de semanas es pública para lectura
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
