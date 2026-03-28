# 🧪 Tests

Tests PHPUnit para el backend OverWorkout API.

## 🏃 Ejecutar Tests

```bash
# Todos los tests
php bin/phpunit

# Tests unitarios
php bin/phpunit --testsuite Unit

# Tests de integración
php bin/phpunit --testsuite Integration

# Tests funcionales (API)
php bin/phpunit --testsuite Functional

# Con cobertura de código
php bin/phpunit --coverage-html coverage/
```

---

## 📁 Estructura

```
tests/
├── Unit/                    # Tests unitarios (sin dependencias)
│   └── Service/
├── Integration/            # Tests con base de datos
│   └── Controller/
├── Functional/            # Tests end-to-end de API
│   └── Api/
│       ├── AuthenticationTest.php    # Login, registro, JWT
│       └── TrainingCrudTest.php      # CRUD de entrenamientos
└── bootstrap.php          # Configuración inicial
```

---

## 🧪 Tests Disponibles

### Functional\Api\AuthenticationTest

Verifica autenticación JWT:
- ✅ Login con credenciales válidas
- ❌ Login con credenciales inválidas
- ❌ Acceso sin token
- ✅ Registro de usuario
- ❌ Registro con datos inválidos

### Functional\Api\TrainingCrudTest

Verifica CRUD de entrenamientos:
- ✅ Crear training
- ✅ Listar trainings propios
- ❌ No ver trainings de otros usuarios
- ✅ Actualizar training propio
- ✅ Eliminar training propio

---

## 🆕 Crear Nuevos Tests

### Test Funcional (API)

```php
<?php
namespace App\Tests\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyFeatureTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/endpoint');
        
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
```

### Test Unitario

```php
<?php
namespace App\Tests\Unit\Service;

use App\Service\MyService;
use PHPUnit\Framework\TestCase;

class MyServiceTest extends TestCase
{
    public function testDoSomething(): void
    {
        $service = new MyService();
        $result = $service->doSomething();
        
        $this->assertSame('expected', $result);
    }
}
```

---

## ⚠️ Notas

- Los tests funcionales usan `APP_ENV=test` con base de datos separada
- Se limpian datos de test automáticamente entre tests
- Los tokens JWT generados en tests son válidos temporalmente
