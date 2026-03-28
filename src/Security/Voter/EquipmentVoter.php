<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Equipments;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, Equipments>
 */
final class EquipmentVoter extends Voter
{
    public const VIEW = 'EQUIPMENT_VIEW';
    public const CREATE = 'EQUIPMENT_CREATE';
    public const EDIT = 'EQUIPMENT_EDIT';
    public const DELETE = 'EQUIPMENT_DELETE';
    public const LIST_ALL = 'EQUIPMENT_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $subject instanceof Equipments,
            self::CREATE, self::LIST_ALL => $subject === null,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        // Todos los equipamientos son públicos para lectura
        if ($attribute === self::VIEW || $attribute === self::LIST_ALL) {
            return true;
        }

        // Solo admins pueden crear, editar y borrar
        return $this->isAdmin($currentUser);
    }

    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
