# Especificación: Sistema de Favoritos — Fase 1

> Fecha: 2026-06-22  
> Estado: Aprobado para implementación  
> Alcance: Fase 1 (favoritos de `Training` y `TrainingProgram`). Sesiones e historial quedan para Fase 2.

---

## Contexto

El frontend reporta el error `Missing required param "sessionId"` cuando un usuario no tiene ningún entrenamiento o programa seleccionado. Se decidió dar al usuario la posibilidad de:

1. Completar un formulario (assessment) y recibir una recomendación.
2. Explorar entrenamientos/programas y elegir los que quiera.
3. Guardar varios entrenamientos/programas como favoritos y consultarlos en una pantalla dedicada.

Esta especificación cubre la **Fase 1**: el sistema de favoritos.

---

## Objetivo

Permitir a un usuario autenticado guardar, eliminar y listar sus entrenamientos (`Training`) y programas (`TrainingProgram`) favoritos.

---

## Nota importante sobre la propiedad de los recursos

Los `Training` y `TrainingProgram` **no los crea el usuario final**. Son creados previamente por administradores o mediante comandos de seeding del sistema. El usuario simplemente los explora en un listado y selecciona cuáles quiere guardar como favoritos.

Por tanto, los endpoints de favoritos siempre operan sobre recursos que ya existen. Si el `Training` o `TrainingProgram` indicado no existe, se devuelve **404**.

---

## Decisiones de diseño

- **Enfoque B: dos entidades separadas** (`UserFavoriteTraining` y `UserFavoriteProgram`).
  - Integridad referencial fuerte mediante FKs reales.
  - Consistente con el estilo actual del proyecto (entidades y voters por recurso).
  - Más explícito y fácil de testear que una entidad polimórfica.

---

## Entidades

### `App\Entity\UserFavoriteTraining`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | int autoincremental | Clave primaria |
| `user` | ManyToOne → `User` | Usuario propietario (no nullable) |
| `training` | ManyToOne → `Training` | Entrenamiento favorito (no nullable) |
| `createdAt` | `DateTimeImmutable` | Fecha de guardado |

- UniqueConstraint en (`user_id`, `training_id`).
- Grupo de serialización: `user_favorite_training:read`.

### `App\Entity\UserFavoriteProgram`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | int autoincremental | Clave primaria |
| `user` | ManyToOne → `User` | Usuario propietario (no nullable) |
| `trainingProgram` | ManyToOne → `TrainingProgram` | Programa favorito (no nullable) |
| `createdAt` | `DateTimeImmutable` | Fecha de guardado |

- UniqueConstraint en (`user_id`, `training_program_id`).
- Grupo de serialización: `user_favorite_program:read`.

---

## Repositorios

- `App\Repository\UserFavoriteTrainingRepository`
- `App\Repository\UserFavoriteProgramRepository`

Métodos comunes:

- `findByUser(User $user): array`
- `findOneByUserAndTraining(User $user, Training $training): ?UserFavoriteTraining`
- `findOneByUserAndProgram(User $user, TrainingProgram $program): ?UserFavoriteProgram`
- `exists(User $user, Training|TrainingProgram $resource): bool`

---

## Controlador

`App\Controller\UserFavoriteApiController`

| Método | Ruta | Verbo | Descripción |
|--------|------|-------|-------------|
| `addTrainingFavorite` | `/user/favorites/trainings/{trainingId}` | POST | Guarda un `Training` como favorito |
| `removeTrainingFavorite` | `/user/favorites/trainings/{favoriteId}` | DELETE | Elimina un favorito de `Training` por su id |
| `addProgramFavorite` | `/user/favorites/programs/{programId}` | POST | Guarda un `TrainingProgram` como favorito |
| `removeProgramFavorite` | `/user/favorites/programs/{favoriteId}` | DELETE | Elimina un favorito de `TrainingProgram` por su id |
| `getUserFavorites` | `/user/favorites` | GET | Devuelve todos los favoritos del usuario |

### Respuestas

**POST /user/favorites/trainings/{trainingId}**
- 201 Created si se crea.
- 200 OK si ya existía (idempotente).
- 404 si el `Training` no existe.

**DELETE /user/favorites/trainings/{favoriteId}**
- 200 OK si se elimina.
- 403 si el favorito no pertenece al usuario autenticado.
- 404 si el favorito no existe.

**DELETE /user/favorites/programs/{favoriteId}**
- 200 OK si se elimina.
- 403 si el favorito no pertenece al usuario autenticado.
- 404 si el favorito no existe.

**GET /user/favorites**
```json
{
  "trainings": [
    {
      "id": 1,
      "training": { /* datos con grupo Training::GROUP_READ */ },
      "createdAt": "2026-06-22T19:00:00+00:00"
    }
  ],
  "programs": [
    {
      "id": 2,
      "trainingProgram": { /* datos con grupo TrainingProgram::GROUP_READ */ },
      "createdAt": "2026-06-22T19:00:00+00:00"
    }
  ]
}
```

---

## Autorización

- `UserFavoriteTrainingVoter` — un usuario solo puede gestionar sus propios favoritos.
- `UserFavoriteProgramVoter` — igual para programas.
- Se reutiliza `UserVoter` para `VIEW/EDIT` del perfil si fuera necesario.

Los endpoints usan `#[IsGranted('ROLE_USER')]` y, en operaciones de escritura, el voter correspondiente.

---

## Tests

Nueva suite: `tests/Functional/Api/UserFavoriteApiTest.php`

Casos mínimos:

1. Añadir un training a favoritos.
2. Añadir el mismo training dos veces es idempotente (no duplica).
3. Eliminar un training de favoritos.
4. Listar favoritos agrupados.
5. Añadir un programa a favoritos.
6. No se pueden ver/modificar favoritos de otro usuario.
7. Devuelve 404 si el recurso no existe.

---

## Migración

`Version20260622190000_CreateUserFavoriteTables.php`

Crea las tablas:
- `user_favorite_training`
- `user_favorite_program`

Con sus respectivas FKs a `user`, `training` y `training_program`, e índices únicos.

---

## Fases futuras

- **Fase 2**: Sesiones de entrenamiento. Permitir iniciar una sesión desde un favorito y persistir el historial.
- **Fase 3**: Integrar favoritos con programas activos (por ejemplo, añadir una sesión de un programa a favoritos).

---

## Notas

- No se requieren DTOs de request; los IDs viajan en la URL.
- Se mantiene el idioma español en mensajes y comentarios, siguiendo `AGENTS.md`.
- Se usa `declare(strict_types=1)` en todos los archivos PHP nuevos.
