<?php

declare(strict_types=1);

namespace App\Enum;

enum TargetWorkout: string
{
    case STRENGTH = 'strength';
    case FATBURNING = 'fatburning';
    case REPBUILDING = 'repbuilding';
    case WARMUP = 'warmup';
}
