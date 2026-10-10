<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Tipos de ítem dentro de los testRequirements de un nivel.
 *
 * Los ítems sin tipo explícito (programas antiguos) se tratan como Fuerza.
 */
enum TestItemType: string
{
    /** Prueba de fuerza/skill con mínimo numérico (reps, segundos…). */
    case Fuerza = 'fuerza';

    /** Marca de benchmark (p. ej. Fran en segundos); puede llevar direction 'lte'. */
    case Benchmark = 'benchmark';

    /** Puerta de movilidad evaluada por primera vez en este nivel (booleana). */
    case PuertaMovilidad = 'puerta_movilidad';

    /** Re-verificación de una puerta ya superada en un nivel anterior. */
    case ReTest = 're_test';

    public function isGate(): bool
    {
        return self::Fuerza !== $this && self::Benchmark !== $this;
    }

    public static function fromNullable(?string $value): self
    {
        return self::tryFrom($value ?? '') ?? self::Fuerza;
    }
}
