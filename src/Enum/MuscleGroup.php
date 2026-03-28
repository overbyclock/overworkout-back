<?php

declare(strict_types=1);

namespace App\Enum;

enum MuscleGroup: string
{
    case CHEST = 'chest';
    case BACK = 'back';
    case LEGS = 'legs';
    case GLUTES = 'glutes';
    case HAMSTRINGS = 'hamstrings';
    case CALVES = 'calves';
    case ADDUCTORS = 'adductors';
    case SHOULDERS = 'shoulders';
    case TRAPS = 'traps';
    case BICEPS = 'biceps';
    case TRICEPS = 'triceps';
    case FOREARMS = 'forearms';
    case CORE = 'core';
    case HIIT = 'hiit';
    case FULL_BODY = 'full_body';
    case NONE = 'none';
}
