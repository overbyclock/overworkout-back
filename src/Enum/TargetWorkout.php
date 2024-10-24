<?php

namespace App\Enum;

enum TargetWorkout: string 
{
  case STRONG = 'strong';
  case FATBURNING = 'fatburning';
  case REPBUILDING = 'repbuilding';
  case WARMUP = 'warmup';
}