# AGENTS.md - OverWorkout Backend API

> Este archivo está destinado a agentes de código AI. El proyecto utiliza español en comentarios, documentación y convenciones de desarrollo.

---

## Descripción general del proyecto

**OverWorkout Backend API** es una API REST privada para una aplicación de gestión de entrenamientos físicos (workouts). El backend gestiona usuarios, ejercicios, equipamiento, entrenamientos y programas de entrenamiento, con soporte para calistenia, CrossFit, HIIT y otras disciplinas.

- **Licencia**: Propietaria (OverWorkout Team)
- **Repositorio**: `overbyclock/overworkout-back`
- **Versión API**: 1.0.0
- **Estado**: En desarrollo activo

---

## Stack tecnológico

| Capa | Tecnología | Versión |
|------|-----------|---------|
| Lenguaje | PHP | >= 8.2 |
| Framework | Symfony | 8.0.* |
| ORM | Doctrine ORM | 3.6+ |
| Base de datos | MySQL / MariaDB | 8.0 / 10.6+ |
| Autenticación | JWT (firebase/php-jwt) | 6.10+ |
| API | Controladores Symfony personalizados (no API Platform activo a pesar de estar instalado) |
| CORS | NelmioCorsBundle | 2.6+ |
| Templates | Twig | 8.0.* |

### Dependencias principales de producción

- `symfony/framework-bundle` (8.0.*) - Core de Symfony
- `api-platform/doctrine-orm` y `api-platform/symfony` (4.0.*) - Instalado pero no activo en recursos actualmente
- `doctrine/orm` (3.6+), `doctrine/dbal` (4+), `doctrine/doctrine-migrations-bundle` (4.0+) - Persistencia
- `firebase/php-jwt` (^6.10) - Generación y validación de tokens JWT
- `nelmio/cors-bundle` (^2.6) - Configuración CORS
- `symfony/security-bundle` (8.0.*) - Sistema de seguridad
- `symfony/validator` (8.0.*) - Validación de DTOs
- `symfony/serializer` (8.0.*) - Normalización de entidades a JSON

### Dependencias de desarrollo

- `phpunit/phpunit` (^11.4) - Testing
- `friendsofphp/php-cs-fixer` (^3.66) - Formateo de código
- `phpstan/phpstan` (^2.1) + `phpstan/phpstan-symfony` (^2.0) - Análisis estático
- `dama/doctrine-test-bundle` (^8.6) - Rollback de transacciones en tests
- `symfony/maker-bundle` (^1.67) - Generación de código

---

## Estructura del proyecto

```
overworkout-back/
├── bin/                          # Scripts de consola Symfony
│   └── console
├── config/                       # Configuración YAML/PHP
│   ├── packages/                # Configuración de bundles
│   ├── routes/                  # Definición de rutas
│   ├── bundles.php              # Registro de bundles
│   ├── services.yaml            # Configuración de servicios y autowiring
│   └── routes.yaml              # Rutas de controladores (atributos PHP)
├── migrations/                  # 17+ migraciones de Doctrine
├── public/                      # Punto de entrada web
│   └── index.php
├── scripts/                     # Scripts de utilidad (NO son migraciones Doctrine)
│   ├── checks/                 # Scripts de verificación de datos
│   ├── cleanup/                # Scripts de limpieza
│   ├── migrations/             # Scripts de migración de datos (PHP/SQL)
│   └── seeders/                # Inserción de datos iniciales/ejercicios
├── src/                         # Código fuente (namespace `App\`)
│   ├── ApiResource/            # Vacío actualmente (.gitignore)
│   ├── Command/                # Comandos de consola Symfony
│   ├── Controller/             # Controladores API REST
│   ├── Dto/Request/            # DTOs de entrada con validación Symfony Validator
│   ├── Entity/                 # Entidades Doctrine (atributos PHP 8)
│   ├── Enum/                   # Enums PHP 8.2 (Discipline, Levels, MuscleGroup, TargetWorkout)
│   ├── EventListener/          # Listeners de eventos (ej. ApiExceptionListener)
│   ├── Mapper/                 # Conversión DTO ↔ Entidad
│   ├── Repository/             # Repositorios Doctrine
│   ├── Security/               # JWTAuthenticator + Voters
│   │   └── Voter/              # Voters de autorización
│   └── Service/                # Servicios de negocio (JWTService, UserPasswordHashService)
├── tests/                       # Tests PHPUnit
│   ├── bootstrap.php           # Inicialización del entorno de test
│   ├── Functional/Api/         # Tests funcionales (WebTestCase)
│   ├── Unit/Dto/               # Tests unitarios de validación de DTOs
│   ├── Unit/Mapper/            # Tests unitarios de mappers
│   └── Unit/Security/Voter/    # Tests unitarios de voters
├── translations/               # Traducciones Symfony
├── var/                        # Caché, logs, sesiones (no versionar)
└── vendor/                     # Dependencias Composer
```

---

## Arquitectura y patrones

### Controladores

- Los controladores extienden `Symfony\Bundle\FrameworkBundle\Controller\AbstractController`.
- Las rutas se definen con **atributos PHP 8** (`#[Route(...)]`) en lugar de anotaciones o YAML.
- Los endpoints devuelven siempre `JsonResponse`.
- Los parámetros de entrada se mapean automáticamente con `#[MapRequestPayload]` a DTOs validados.
- La autorización se controla con `#[IsGranted(...)]` o `$this->denyAccessUnlessGranted(...)` usando Voters personalizados.

### DTOs y Mappers

- Los DTOs de request están en `src/Dto/Request/`.
- Son clases `readonly` con propiedades públicas y atributos de validación de Symfony Validator (`#[Assert\...]`).
- Los mensajes de validación están en **español**.
- Los Mappers en `src/Mapper/` convierten DTOs a Entidades y actualizan entidades desde DTOs de actualización parcial.

### Entidades

- Ubicadas en `src/Entity/`.
- Mapping de Doctrine mediante **atributos PHP 8** (`#[ORM\Entity]`, `#[ORM\Column]`, etc.).
- Uso de `Symfony\Component\Serializer\Attribute\Groups` para controlar la exposición JSON.
- Las relaciones OneToMany/ManyToOne están tipadas con `Collection<int, Entity>`.

### Seguridad

- **Autenticación JWT**: Custom `JWTAuthenticator` que lee el header `Authorization: Bearer <token>`.
- **Login**: `POST /login` con email y password. Devuelve token JWT + datos de usuario.
- **Registro**: `POST /register` sin autenticación.
- **Roles**: `ROLE_USER` (por defecto) y `ROLE_ADMIN`.
- **Voters**: Autorización granular por recurso:
  - `TrainingVoter`: dueño del recurso o admin
  - `EquipmentVoter`: admins editan, usuarios solo ven
  - `ExerciseVoter`: similar a Equipment
  - `UserVoter`: usuarios solo ven/editan su propio perfil

### Manejo de excepciones

- `ApiExceptionListener` captura excepciones en peticiones JSON y devuelve respuestas JSON estructuradas:
  - `ValidationFailedException` → 422 con lista de violaciones
  - `AccessDeniedException` → 403
  - `HttpExceptionInterface` → código HTTP correspondiente
  - `InvalidArgumentException` → 400

---

## Comandos de build y desarrollo

### Instalación inicial

```bash
composer install
# Configurar .env.local con credenciales de base de datos
cp .env .env.local
# Generar clave JWT
php bin/console secrets:set JWT_SECRET_KEY
# openssl rand -base64 32  (para generar valor seguro)

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
# Opcional: cargar datos iniciales
php bin/console doctrine:fixtures:load
```

### Servidor de desarrollo

```bash
# Opción A: Symfony CLI (recomendado)
symfony server:start

# Opción B: PHP nativo
php -S localhost:8000 -t public/
```

### Comandos útiles de Symfony

```bash
php bin/console cache:clear
php bin/console make:entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:validate
```

### Scripts de utilidad

La carpeta `scripts/` contiene scripts PHP/SQL de mantenimiento que NO son migraciones de Doctrine. Se ejecutan manualmente según sea necesario:

```bash
php scripts/seeders/insert_exercises.php
php scripts/checks/check_equipment.php
php scripts/migrations/update_exercises.php
```

---

## Guías de estilo de código

### PHP-CS-Fixer

Configuración en `.php-cs-fixer.dist.php`:

- Reglas base: `@PSR12` + `@Symfony`
- `declare_strict_types` → **obligatorio** en todos los archivos PHP
- `strict_comparison` → comparaciones estrictas (`===`, `!==`)
- `strict_param` → funciones con tipado estricto
- `array_syntax` → sintaxis corta `[]`
- `no_unused_imports` → eliminar imports sin usar
- `ordered_imports` → orden alfabético
- `single_quote` → comillas simples cuando sea posible
- `trailing_comma_in_multiline` → coma final en arrays multilinea
- `native_function_invocation` → funciones nativas optimizadas por el compilador con `\`

### Comandos

```bash
# Formatear código
vendor/bin/php-cs-fixer fix

# Verificar sin modificar (modo CI)
vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php-cs-fixer.dist.php
```

### Convenciones del proyecto

- Todos los archivos PHP deben comenzar con `<?php` y `declare(strict_types=1);`
- Namespace: `App\` mapeado a `src/` (PSR-4)
- Tests: `App\Tests\` mapeado a `tests/`
- Inyección de dependencias vía constructor (autowiring activo)
- Clases final cuando no se espera herencia
- Uso de tipado estricto en propiedades, parámetros y retornos
- Comentarios y mensajes de error en **español**

---

## Testing

### Estructura de tests

| Suite | Ubicación | Tipo |
|-------|-----------|------|
| Unit | `tests/Unit/` | Tests unitarios puros (PHPUnit\TestCase) |
| Integration | `tests/Integration/` | Suite configurada pero vacía actualmente |
| Functional | `tests/Functional/Api/` | Tests funcionales (WebTestCase) |

### Tests existentes

- **Functional/Api/AuthenticationTest.php**: Login, registro, JWT, acceso protegido (5 tests)
- **Functional/Api/TrainingCrudTest.php**: CRUD completo de entrenamientos (5 tests)
- **Unit/Dto/**: Validación de DTOs (47 tests)
- **Unit/Mapper/**: Conversión DTO↔Entity (18 tests)
- **Unit/Security/Voter/**: Autorización de 4 Voters (59 tests)

### Configuración

- `phpunit.xml.dist` y `phpunit.dist.xml` coexisten. `phpunit.dist.xml` es la configuración principal con `DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension` para rollback de transacciones en tests funcionales.
- Entorno de test forzado a `APP_ENV=test` en `tests/bootstrap.php`.
- En tests, los password hashers usan coste mínimo (configurado en `security.yaml` bajo `when@test`).

### Comandos

```bash
# Todos los tests
php bin/phpunit

# Solo tests unitarios
vendor/bin/phpunit tests/Unit --no-coverage --testdox

# Solo tests funcionales
vendor/bin/phpunit tests/Functional --no-coverage --testdox

# Con cobertura
php bin/phpunit --coverage-html coverage/

# Tests específicos
php bin/phpunit tests/Security/AuthenticationTest.php
```

---

## Análisis estático (PHPStan)

- Configuración: `phpstan.neon.dist`
- Nivel por defecto: **5**
- Paths analizados: `src`, `tests/Unit`
- Excluye: `src/Command`, `tests/bootstrap.php`, `tests/Functional`
- Extiende `phpstan-symfony` con container XML desde caché de dev
- Ignore patterns configurados para repositories auto-generados y propiedades de Doctrine asignadas por reflexión

```bash
# Análisis estándar
vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --no-progress --memory-limit=1G

# Nivel más estricto
vendor/bin/phpstan analyse --level=8
```

---

## CI/CD (GitHub Actions)

Workflow: `.github/workflows/ci.yml`

Se ejecuta en **push** a `main`/`develop` y en **pull request** a `main`.

### Jobs

1. **code-style**: Verifica formato con PHP-CS-Fixer (`--dry-run --diff`)
2. **static-analysis**: PHPStan nivel 5 con `.env` generado para CI
3. **unit-tests**: PHPUnit sobre `tests/Unit` sin cobertura, con SQLite en memoria

### Variables de entorno en CI

El workflow genera un `.env` con:
- `APP_ENV=test` o `dev`
- `APP_SECRET=test-secret-for-ci-only`
- `JWT_SECRET_KEY=test-jwt-key-for-ci-only-change-in-production`
- `DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db`

---

## Seguridad

### Autenticación

- JWT tokens generados con `firebase/php-jwt` (algoritmo por defecto del servicio).
- Payload del token incluye: `user_id`, `email`, `roles`.
- El secret se configura mediante `JWT_SECRET_KEY` (variable de entorno o secrets de Symfony).
- Duración del token: verificar en `JWTService::generateToken()`.

### Autorización

| Voter | Permisos |
|-------|----------|
| `UserVoter` | VIEW/EDIT/DELETE propio perfil; LIST_ALL solo admin |
| `TrainingVoter` | VIEW propio o público; EDIT/DELETE propio; LIST_ALL solo admin |
| `EquipmentVoter` | VIEW todos; EDIT/DELETE solo admin |
| `ExerciseVoter` | Similar a EquipmentVoter |

### CORS

Configurado en `config/packages/nelmio_cors.yaml`:
- Orígenes: definidos por variable de entorno `CORS_ALLOW_ORIGIN`
- Por defecto en `.env`: `^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$`
- Métodos permitidos: GET, POST, PUT, PATCH, DELETE, OPTIONS
- Headers: Content-Type, Authorization

### Firewall (security.yaml)

- `^/(_(profiler\|wdt)\|css\|images\|js)/` → sin seguridad (dev)
- `^/register` y `^/login` → stateless, sin seguridad
- Resto de rutas (`^/`) → stateless, autenticación JWT obligatoria

### Consideraciones de despliegue

- **NUNCA** commitear secretos en `.env` o archivos versionados.
- Usar `php bin/console secrets:set` para gestionar secretos en producción.
- En producción: `composer install --no-dev --optimize-autoloader`
- Limpiar caché tras despliegue: `php bin/console cache:clear --env=prod`

---

## Configuración de entorno

### Jerarquía de archivos .env

1. `.env` (defaults, versionado)
2. `.env.local` (overrides locales, **NO versionar**)
3. `.env.$APP_ENV` (defaults por entorno, versionado)
4. `.env.$APP_ENV.local` (overrides por entorno, **NO versionar**)

### Variables clave

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `APP_ENV` | Entorno (dev, test, prod) | `dev` |
| `APP_SECRET` | Secret de Symfony | generado |
| `JWT_SECRET_KEY` | Clave para firmar JWT | `openssl rand -base64 32` |
| `DATABASE_URL` | Conexión a base de datos | `mysql://root@127.0.0.1:3306/overworkout?serverVersion=8.0.32&charset=utf8mb4` |
| `CORS_ALLOW_ORIGIN` | Regex de orígenes CORS | `'^https?://(localhost\|127\.0\.0\.1)(:[0-9]+)?$'` |

### Base de datos

- Motor principal: **MySQL 8.0** (también compatible MariaDB 10.6+)
- En tests/CI se puede usar **SQLite** para tests unitarios/funcionales simples
- El `schema_filter` en `doctrine.yaml` excluye tablas internas de migraciones y algunas tablas de features no activos
- Sufijo de base de datos en test: `_test` (o `_test{N}` con ParaTest)

---

## Notas para agentes AI

- **Idioma**: Escribe comentarios, mensajes de error y documentación en **español**, consistente con el resto del proyecto.
- **No asumir API Platform**: Aunque está instalado, los endpoints actuales son controladores Symfony personalizados. No crear recursos API Platform sin confirmación.
- **Voters**: Antes de exponer un nuevo endpoint, evaluar si necesita un Voter en `src/Security/Voter/`.
- **DTOs**: Para nuevos endpoints de escritura, crear un DTO en `src/Dto/Request/` con validación Symfony Validator y un Mapper correspondiente.
- **Tests**: Añadir tests unitarios para DTOs, Mappers y Voters. Añadir tests funcionales (WebTestCase) para endpoints HTTP.
- **Migrations**: Usar `php bin/console make:migration` para cambios de schema. Los scripts en `scripts/migrations/` son para transformación de datos, no para schema.
- **Calistenia**: El proyecto tiene documentación extensa sobre un sistema de calistenia (`CALISTHENIA_MASTER_SYSTEM.md`, `CALISTHENIA_ROADMAP.md`, etc.). Consultar esos archivos antes de modificar entidades relacionadas con niveles, skills o benchmarks.
