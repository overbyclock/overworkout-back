<?php

namespace App\Enum;

enum MuscleGroup: string
{
  case CHEST = 'chest';
  case BACK = 'back';
  case LEGS = 'legs';
  case SHOULDERS = 'shoulders';
  case BICEPS = 'biceps';
  case TRICEPS = 'triceps';
  case CORE = 'core';
  case FULL_BODY = 'full_body';
  case NONE = 'none';
}