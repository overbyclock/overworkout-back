# 🏋️ OverWorkout Backend API

Backend API REST privada para la aplicación de gestión de entrenamientos OverWorkout. Construido con Symfony 7.1, DTOs, Voters y JWT Authentication.

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg?style=flat&logo=php)](https://php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-7.1-000000.svg?style=flat&logo=symfony)](https://symfony.com/)
[![CI](https://github.com/overbyclock/overworkout-back/actions/workflows/ci.yml/badge.svg)](https://github.com/overbyclock/overworkout-back/actions)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg)](https://phpstan.org/)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-brightgreen.svg)](https://www.php-fig.org/psr/psr-12/)

---

## 📋 Requisitos

- **PHP**: 8.2 o superior
- **Composer**: 2.x
- **MySQL**: 8.0 o MariaDB 10.6+
- **Symfony CLI**: (opcional pero recomendado)

---

## 🚀 Instalación Rápida

### 1. Clonar y dependencias

```bash
git clone https://github.com/overbyclock/overworkout-back.git
cd overworkout-back
composer install
```

### 2. Configurar entorno

```bash
# Copiar archivo de entorno
cp .env .env.local

# Editar .env.local con tus credenciales de base de datos
DATABASE_URL="mysql://usuario:password@127.0.0.1:3306/overworkout_db?serverVersion=8.0"

# Generar clave JWT
php bin/console secrets:set JWT_SECRET_KEY
# Cuando te pida el valor, genera uno seguro con:
# openssl rand -base64 32
```

### 3. Crear base de datos

```bash
# Crear la base de datos
php bin/console doctrine:database:create

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate

# Cargar datos iniciales (opcional)
php bin/console doctrine:fixtures:load
```

### 4. Iniciar servidor

```bash
# Opción A: Con Symfony CLI (recomendado)
symfony server:start

# Opción B: Con PHP nativo
php -S localhost:8000 -t public/
```

La API estará disponible en: `http://localhost:8000`

---

## 📁 Estructura del Proyecto

```
overworkout-back/
├── bin/                    # Scripts de consola Symfony
├── config/                 # Configuración (YAML/PHP)
│   ├── packages/          # Config de bundles
│   └── routes/            # Rutas
├── migrations/            # Migraciones de Doctrine
├── public/                # Punto de entrada web
│   └── index.php
├── scripts/               # Scripts de utilidad (datos, fixes)
│   ├── migrations/        # Scripts de migración de datos
│   ├── seeders/          # Datos iniciales
│   └── checks/           # Scripts de verificación
├── src/
│   ├── ApiResource/       # Configuración API Platform
│   ├── Command/           # Comandos de consola Symfony
│   ├── Controller/        # Controladores API
│   ├── DTO/              # Data Transfer Objects (Request/Response)
│   ├── Entity/            # Entidades Doctrine
│   ├── Enum/              # Enums PHP 8
│   ├── Repository/        # Repositorios Doctrine
│   ├── Security/          # JWT Authenticator, Voters
│   └── Service/           # Lógica de negocio
├── tests/                 # Tests PHPUnit
├── translations/          # Traducciones
├── var/                   # Cache, logs, sessions
└── vendor/                # Dependencias Composer
```

---

## 🔐 Autenticación

La API usa **JWT (JSON Web Tokens)** para autenticación.

### Login

```http
POST /api/login
Content-Type: application/json

{
  "nick": "usuario",
  "password": "contraseña"
}
```

**Respuesta:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
  "userId": 1,
  "nick": "usuario",
  "roles": ["ROLE_ADMIN"]
}
```

### Uso del Token

Incluir el token en el header de todas las peticiones:

```http
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

---

## 📚 Endpoints Principales

### Endpoints por Recurso

| Recurso | Listar | Ver | Crear | Actualizar | Eliminar |
|---------|--------|-----|-------|------------|----------|
| **Users** | GET `/api/users` | GET `/api/users/{id}` | POST `/api/users` | PATCH `/api/users/{id}` | DELETE `/api/users/{id}` |
| **Exercises** | GET `/api/exercises` | GET `/api/exercises/{id}` | POST `/api/exercises` | PATCH `/api/exercises/{id}` | DELETE `/api/exercises/{id}` |
| **Equipments** | GET `/api/equipments` | GET `/api/equipments/{id}` | POST `/api/equipments` | PATCH `/api/equipments/{id}` | DELETE `/api/equipments/{id}` |
| **Trainings** | GET `/api/trainings` | GET `/api/trainings/{id}` | POST `/api/trainings` | PATCH `/api/trainings/{id}` | DELETE `/api/trainings/{id}` |
| **Programs** | GET `/api/training-programs` | GET `/api/training-programs/{id}` | POST `/api/training-programs` | PATCH `/api/training-programs/{id}` | DELETE `/api/training-programs/{id}` |

---

## 🛠️ Comandos Útiles

### Desarrollo

```bash
# Limpiar caché
php bin/console cache:clear

# Crear nueva entidad
php bin/console make:entity

# Crear migración
php bin/console make:migration

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate

# Validar mapping de Doctrine
php bin/console doctrine:schema:validate
```

### Tests

```bash
# Ejecutar todos los tests
php bin/phpunit

# Ejecutar tests con cobertura
php bin/phpunit --coverage-html coverage/

# Ejecutar tests específicos
php bin/phpunit tests/Security/AuthenticationTest.php
```

### Calidad de Código

```bash
# Formatear código con PHP-CS-Fixer
vendor/bin/php-cs-fixer fix

# Verificar estilo sin modificar archivos
vendor/bin/php-cs-fixer fix --dry-run --diff

# Análisis estático con PHPStan (nivel 5)
vendor/bin/phpstan analyse --level=5

# Análisis más estricto (nivel 8)
vendor/bin/phpstan analyse --level=8
```

---

## 🔧 Scripts de Utilidad

Los scripts en la carpeta `scripts/` sirven para tareas de mantenimiento:

```bash
# Scripts de migración de datos
php scripts/migrations/migrate_data.php

# Scripts de verificación
php scripts/checks/check_equipment.php

# Scripts de seeders (datos de prueba)
php scripts/seeders/load_exercises.php
```

---

## 🧪 Testing

### Tests Implementados

#### Tests Funcionales (10 tests)
- ✅ **AuthenticationTest**: Login, registro, JWT (5 tests)
- ✅ **TrainingCrudTest**: CRUD completo de entrenamientos (5 tests)

#### Tests Unitarios (124 tests)
- ✅ **DTOs**: Validación de 11 DTOs (47 tests)
- ✅ **Mappers**: Conversión DTO↔Entity (18 tests)
- ✅ **Voters**: Autorización de 4 Voters (59 tests)

**Total: 134 tests, 195+ assertions**

### Estructura de Tests

```
tests/
├── Unit/                    # 124 tests unitarios
│   ├── Dto/                 # Validación de DTOs
│   ├── Mapper/              # Tests de mappers
│   └── Security/Voter/      # Tests de autorización
└── Functional/              # 10 tests funcionales
    └── Api/
        ├── AuthenticationTest.php
        └── TrainingCrudTest.php
```

---

## 🔄 CI/CD

El proyecto utiliza **GitHub Actions** para integración continua. El workflow se ejecuta en cada push y pull request a las ramas `main` y `develop`.

### Jobs del Pipeline

| Job | Descripción | Comando |
|-----|-------------|---------|
| **Code Style** | Verifica formato PSR-12 | `php-cs-fixer fix --dry-run` |
| **Static Analysis** | Análisis estático PHPStan nivel 5 | `phpstan analyse` |
| **Unit Tests** | Tests unitarios (sin dependencias externas) | `phpunit tests/Unit` |
| **Functional Tests** | Tests funcionales (requiere MySQL) | `phpunit tests/Functional` |

### Estado del Pipeline

Haz clic en el badge de CI arriba para ver el estado actual de los workflows.

### Ejecutar checks localmente

```bash
# Todos los checks (igual que en CI)
vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/phpstan analyse --level=5
vendor/bin/phpunit tests/Unit --no-coverage
```

---

## 🔒 Seguridad

### Roles

| Rol | Descripción |
|-----|-------------|
| `ROLE_USER` | Usuario estándar, acceso a sus propios recursos |
| `ROLE_ADMIN` | Administrador, acceso completo a todos los recursos |

### Voters

Los voters controlan el acceso granular a recursos:

- **TrainingVoter**: Usuarios solo pueden modificar sus propios entrenamientos
- **EquipmentVoter**: Administradores pueden modificar, usuarios solo ver
- **UserVoter**: Usuarios solo pueden ver/editar su propio perfil

### CORS

Configurado en `config/packages/nelmio_cors.yaml`. Por defecto permite:
- Origen: `http://localhost:5173` (frontend Vite dev server)
- Métodos: GET, POST, PUT, PATCH, DELETE, OPTIONS
- Headers: Authorization, Content-Type

---

## 📦 Dependencias Principales

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| `symfony/framework-bundle` | 7.1.* | Core de Symfony |
| `api-platform/core` | 4.0.* | API REST automática |
| `doctrine/orm` | 3.* | ORM para base de datos |
| `firebase/php-jwt` | ^6.0 | Autenticación JWT |
| `nelmio/cors-bundle` | ^2.0 | Configuración CORS |
| `symfony/security-bundle` | 7.1.* | Sistema de seguridad |
| `symfony/validator` | 7.1.* | Validación de datos |
| `symfony/serializer` | 7.1.* | Serialización JSON |

---

## 🐳 Docker (Opcional)

Si prefieres usar Docker:

```bash
# Construir imágenes
docker-compose build

# Iniciar servicios
docker-compose up -d

# Ejecutar migraciones
docker-compose exec php php bin/console doctrine:migrations:migrate
```

Servicios incluidos:
- PHP 8.2-FPM
- Nginx
- MySQL 8.0
- Redis (opcional, para caché)

---

## 🤝 Contribución

### Guía de Estilo

- PSR-12 para estilo de código
- PHP 8.2+ features (enums, attributes, named arguments)
- Tipado estricto (`declare(strict_types=1)`)
- Inyección de dependencias vía constructor

### Proceso

1. Crear rama: `git checkout -b feature/nueva-funcionalidad`
2. Hacer cambios con tests
3. Ejecutar: `composer check` (corre tests, lint y phpstan)
4. Commit: `git commit -m "feat: descripción"`
5. Push y Pull Request

---

## 📄 Licencia

Proyecto privado - OverWorkout Team

---

## 💬 Soporte

- Documentación Symfony: https://symfony.com/doc/current/
- Documentación API Platform: https://api-platform.com/docs/
- Issues: Crear issue en el repositorio

---

**Última actualización:** 2026-03-28  
**Versión API:** 1.0.0  
**Estado:** En desarrollo activo 🚀
