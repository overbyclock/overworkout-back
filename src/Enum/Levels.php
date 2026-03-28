<?php

declare(strict_types=1);

namespace App\Enum;

enum Levels: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case EXPERT = 'expert';
    case NOLEVEL = 'nolevel';
}
