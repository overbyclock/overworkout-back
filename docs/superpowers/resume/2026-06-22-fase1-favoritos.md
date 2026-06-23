# Resumen de sesión — Fase 1: Sistema de Favoritos

> Fecha: 2026-06-22  
> Estado: Plan listo para ejecutar. Esperando decisión del usuario sobre modo de ejecución.

---

## Contexto

El frontend reporta el error `Missing required param "sessionId"` cuando un usuario no tiene ningún entrenamiento o programa seleccionado. Se acordó implementar un sistema por fases:

1. **Fase 1 (actual):** Sistema de favoritos de `Training` y `TrainingProgram`.
2. **Fase 2 (posterior):** Sesiones de entrenamiento e historial.
3. **Fase 3 (posterior):** Integración con programas activos.

---

## Decisiones tomadas

- **Enfoque B:** Dos entidades separadas (`UserFavoriteTraining` y `UserFavoriteProgram`) en lugar de una entidad polimórfica.
- **Los recursos no los crea el usuario:** `Training` y `TrainingProgram` son creados por admins/seeders; el usuario solo los selecciona de un listado.
- **DELETE por `favoriteId`:** Las rutas de eliminación usan el id del favorito (`/user/favorites/trainings/{favoriteId}`) para poder ejercitar el voter y facilitar el frontend.

---

## Documentos creados

- **Spec:** `docs/superpowers/specs/2026-06-22-sistema-favoritos-design.md`
- **Plan:** `docs/superpowers/plans/2026-06-22-sistema-favoritos.md`

---

## Próximos pasos

1. El usuario debe elegir modo de ejecución del plan:
   - **Subagent-Driven (recomendado):** un subagente por tarea, revisión entre tareas.
   - **Inline Execution:** ejecución en la sesión actual con checkpoints.
2. Ejecutar el plan tarea por tarea, empezando por la **Task 1** (migración de base de datos).

---

## Endpoints a implementar

| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/user/favorites/trainings/{trainingId}` | Añade training a favoritos |
| DELETE | `/user/favorites/trainings/{favoriteId}` | Elimina training de favoritos |
| POST | `/user/favorites/programs/{programId}` | Añade programa a favoritos |
| DELETE | `/user/favorites/programs/{favoriteId}` | Elimina programa de favoritos |
| GET | `/user/favorites` | Lista favoritos del usuario |

---

## Archivos previstos a crear/modificar

- `migrations/Version20260622200000.php`
- `src/Entity/UserFavoriteTraining.php`
- `src/Entity/UserFavoriteProgram.php`
- `src/Repository/UserFavoriteTrainingRepository.php`
- `src/Repository/UserFavoriteProgramRepository.php`
- `src/Security/Voter/UserFavoriteTrainingVoter.php`
- `src/Security/Voter/UserFavoriteProgramVoter.php`
- `src/Controller/UserFavoriteApiController.php`
- `tests/Unit/Entity/UserFavoriteTrainingTest.php`
- `tests/Unit/Entity/UserFavoriteProgramTest.php`
- `tests/Unit/Security/Voter/UserFavoriteTrainingVoterTest.php`
- `tests/Unit/Security/Voter/UserFavoriteProgramVoterTest.php`
- `tests/Functional/Api/UserFavoriteApiTest.php`
- `src/Entity/User.php` (relaciones inversas)

---

## Comandos de validación finales

```bash
php bin/phpunit --no-coverage
vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --no-progress --memory-limit=1G
vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php-cs-fixer.dist.php
```
