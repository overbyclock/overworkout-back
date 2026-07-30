# Instrucciones para la IA de programación — Calistenia Master v4.0

> Pega este texto (o el fragmento que necesites) como prompt inicial a tu IA de programación, con los archivos de esta carpeta accesibles en el workspace del proyecto.

---

## Qué vas a construir

Un programa de entrenamiento de calistenia por niveles llamado **Calistenia Master v4.0**: 15 niveles + un bloque élite abierto ("Olimpo"), con ciclos ilimitados, tests de nivel con mínimos objetivos, puertas de movilidad y un sistema de logros (skills desbloqueables). El diseño ya está completo y cerrado; tu trabajo es implementarlo, no rediseñarlo.

## Archivos fuente (léelos antes de escribir código)

| Archivo | Contenido | Cuándo usarlo |
|---|---|---|
| `arquitectura_niveles_calistenia.md` | **FUENTE DE VERDAD.** Niveles, tests (mínimos/objetivos), puertas de movilidad G1-G6 y re-tests, mapa de skills/logros, reglas de diseño §1 | Siempre. Empieza aquí |
| `detalle_niveles_01-03.md` | Sesiones completas de los niveles 1-3 (16 por nivel) + fichas de ejercicios | Al generar el contenido de esos niveles |
| `detalle_niveles_04-06.md` | Ídem niveles 4-6 | Ídem |
| `detalle_niveles_07-09.md` | Ídem niveles 7-9 | Ídem |
| `detalle_niveles_10-12.md` | Ídem niveles 10-12 | Ídem |
| `detalle_niveles_13-15_olimpo.md` | Ídem niveles 13-15 + Bloque Olimpo | Ídem |
| `calistenia_master_v4_final.md` | Todo lo anterior en un solo archivo (por si prefieres una sola fuente) | Alternativa a los 6 anteriores |

Los demás archivos del directorio de origen (auditorías, revisiones, plantilla, plan) son documentos de trabajo internos: **ignóralos**.

## Reglas de negocio que debes respetar

1. **Sin calendario**: el programa no usa días/semanas/meses. El progreso es por fases y ciclos; cada usuario avanza a su ritmo. No generes planificación temporal.
2. **Estructura**: Nivel → 4 fases (Base → Progresión → Intensificación → Recuperación) → 4 sesiones por fase (A empuje, B tracción, C circuito, D skill). Ciclos ilimitados hasta superar el test.
3. **Test de nivel**: cada nivel tiene pruebas con `mínimo` (obligatorio para avanzar) y `objetivo` (excelencia). Fallo = repetir nivel (estado `repeat`), sin límite de intentos.
4. **Puertas de movilidad (G1-G6)**: se evalúan dentro de tests. Si el usuario supera la fuerza pero falla una puerta → estado "bloqueado por movilidad": ciclo corto de movilidad sin repetir la fuerza ya superada. Las puertas tienen re-tests en tests posteriores (G1+G2 en N9, G4 en N10, G3+G5+G6 en N12).
5. **Logros**: dos tipos — de test (al superar el nivel) y de hito (primera ejecución de un movimiento en sesión). Tiers: Común, Poco común, Raro, Épico, Legendario. Hitos con celebración especial: Primera Dominada (N4), Muscle-Up (N9), Full Front Lever + Full Planche (N12), One-Arm Pull-Up (N15).
6. **Toda sesión tiene 5 bloques**: Bloque 0 (preparación/movilidad), principal, salud postural, cadena posterior/cadera, descarga. Ninguno es opcional.
7. **Ejercicios**: cada uno tiene ficha completa (descripción, preparación, ejecución, estándares, errores comunes, consejos de seguridad, recursos). Las fichas están en el nivel donde el ejercicio aparece por primera vez; niveles posteriores la referencian.
8. **Las marcas de los tests no se modifican**: son las literales de la arquitectura.

## Modelo de datos sugerido (orientativo)

- `Program` → `Level` (1-15, ∞) → `Phase` (1-4) → `Session` (A-D) → `Block` (0, main, postural, posterior, cooldown) → `SessionExercise` (series, reps/tiempo, descanso, notas).
- `Exercise` con su ficha completa.
- `LevelTest` → `TestItem` (nombre, mínimo, objetivo, tipo: fuerza | puerta_movilidad | re_test).
- `Achievement` (tipo: test | hito; tier; nivel asociado).
- `UserLevelProgress` (estado: locked / in_progress / completed / repeat / mobility_blocked) y `UserTrainingLog`.
- `MobilityGate` (G1-G6) con criterio de evaluación y niveles donde se testea/re-testea.

## Orden de trabajo recomendado

1. Lee `arquitectura_niveles_calistenia.md` completo y confirma el modelo de datos.
2. Implementa la estructura (programa, niveles, fases, sesiones, bloques, tests, logros, estados).
3. Carga el contenido por lotes: primero `detalle_niveles_01-03.md`, valida conmigo, y continúa con los siguientes.
4. Deja Olimpo para el final (tiene estructura propia: especialización rotativa, rangos, récords).

Ante cualquier conflicto entre documentos, la arquitectura manda. Si algo te falta o no cuadra, pregúntame antes de inventarlo.
