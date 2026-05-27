<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TrainingProgram;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, TrainingProgram>
 */
final class TrainingProgramVoter extends Voter
{
    public const VIEW = 'PROGRAM_VIEW';
    public const CREATE = 'PROGRAM_CREATE';
    public const EDIT = 'PROGRAM_EDIT';
    public const DELETE = 'PROGRAM_DELETE';
    public const LIST_ALL = 'PROGRAM_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof TrainingProgram,
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

        // Los programas son públicos para lectura
        if (self::VIEW === $attribute || self::LIST_ALL === $attribute) {
            return true;
        }

        // Solo admins pueden crear, editar y borrar programas
        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
