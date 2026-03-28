<?php

declare(strict_types=1);

namespace App\Enum;

enum Discipline: string
{
    case CALISTHENICS = 'calisthenics';
    case CROSSFIT = 'crossfit';
    case FITNESS = 'fitness';
    case CALISTHENICSFITNESS = 'calisthenicsfitness';
}
