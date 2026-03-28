<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Training;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, mixed>
 */
final class TrainingVoter extends Voter
{
    public const VIEW = 'TRAINING_VIEW';
    public const CREATE = 'TRAINING_CREATE';
    public const EDIT = 'TRAINING_EDIT';
    public const DELETE = 'TRAINING_DELETE';
    public const LIST_ALL = 'TRAINING_LIST_ALL';
    public const LIST_USER = 'TRAINING_LIST_USER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof Training,
            self::CREATE, self::LIST_ALL => null === $subject,
            self::LIST_USER => \is_int($subject) || \is_string($subject) || null === $subject,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $currentUser),
            self::CREATE => $this->canCreate($currentUser),
            self::EDIT => $this->canEdit($subject, $currentUser),
            self::DELETE => $this->canDelete($subject, $currentUser),
            self::LIST_ALL => $this->canListAll($currentUser),
            self::LIST_USER => $this->canListUser($subject, $currentUser),
            default => false,
        };
    }

    private function canView(Training $training, UserInterface $currentUser): bool
    {
        // Los entrenamientos públicos (sin usuario asignado) son visibles por todos
        if (null === $training->getTrainingUser()) {
            return true;
        }

        // Los usuarios pueden ver sus propios entrenamientos
        // Los admins pueden ver cualquier entrenamiento
        return $this->isAdmin($currentUser) || $this->isOwner($training, $currentUser);
    }

    private function canCreate(UserInterface $currentUser): bool
    {
        // Cualquier usuario autenticado puede crear entrenamientos
        return true;
    }

    private function canEdit(Training $training, UserInterface $currentUser): bool
    {
        // Solo el dueño o admin pueden editar
        return $this->isAdmin($currentUser) || $this->isOwner($training, $currentUser);
    }

    private function canDelete(Training $training, UserInterface $currentUser): bool
    {
        // Solo el dueño o admin pueden borrar
        return $this->isAdmin($currentUser) || $this->isOwner($training, $currentUser);
    }

    private function canListAll(UserInterface $currentUser): bool
    {
        // Solo admins pueden listar todos los entrenamientos
        return $this->isAdmin($currentUser);
    }

    private function canListUser(int|string|null $userId, UserInterface $currentUser): bool
    {
        // Los usuarios pueden ver sus propios entrenamientos
        // Los admins pueden ver los de cualquiera
        if ($this->isAdmin($currentUser)) {
            return true;
        }

        if (!$currentUser instanceof User) {
            return false;
        }

        return $currentUser->getId() === (int) $userId;
    }

    private function isAdmin(UserInterface $user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    private function isOwner(Training $training, UserInterface $currentUser): bool
    {
        if (!$currentUser instanceof User) {
            return false;
        }

        $trainingUser = $training->getTrainingUser();

        return null !== $trainingUser && $trainingUser->getId() === $currentUser->getId();
    }
}
