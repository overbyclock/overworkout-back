<?php

declare(strict_types=1);

namespace App\Enum;

enum BenchmarkFormat: string
{
    case FOR_TIME = 'for_time';
    case AMRAP = 'amrap';
    case EMOM = 'emom';
    case TABATA = 'tabata';
    case CHIPPER = 'chipper';
    case LADDER = 'ladder';
    case INTERVAL_STATIONS = 'interval_stations';
}
