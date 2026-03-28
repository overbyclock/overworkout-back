# 🛠️ Scripts de Utilidad

Esta carpeta contiene scripts PHP para tareas de mantenimiento, migración de datos y verificación.

> ⚠️ **Nota**: Estos scripts están pensados para ejecución manual o one-off. No forman parte de la aplicación principal.

---

## 📁 Estructura

```
scripts/
├── checks/          # Scripts de verificación y diagnóstico
├── cleanup/         # Scripts de limpieza y eliminación
├── legacy/          # Scripts obsoletos (no usar)
├── migrations/      # Migraciones de datos y estructura
└── seeders/         # Datos iniciales (fixtures)
```

---

## 🔍 Checks (`checks/`)

Scripts para verificar el estado de los datos.

| Script | Descripción |
|--------|-------------|
| `check_calisthenics.php` | Verifica datos de calistenia |
| `check_columns.php` | Verifica estructura de columnas |
| `check_crossfit.php` | Verifica datos de crossfit |
| `check_dumbbell.php` | Verifica equipamiento dumbbell |
| `check_equipment.php` | Verificación general de equipamiento |
| `check_fitness.php` | Verifica datos de fitness |
| `check_hiit.php` | Verifica ejercicios HIIT |
| `check_legs.php` | Verifica ejercicios de piernas |
| `check_tables.php` | Verifica estructura de tablas |
| `list_equipment.php` | Lista todo el equipamiento |
| `test_api.php` | Test básico de la API |

**Uso:**
```bash
php scripts/checks/check_equipment.php
```

---

## 🧹 Cleanup (`cleanup/`)

Scripts para limpiar datos duplicados o incorrectos.

| Script | Descripción |
|--------|-------------|
| `cleanup_fitness.php` | Limpieza de datos fitness |
| `remove_archer.php` | Elimina ejercicios archer |
| `remove_duplicate.php` | Elimina duplicados (versión 1) |
| `remove_duplicates.php` | Elimina duplicados (versión 2) |

**Uso:**
```bash
php scripts/cleanup/remove_duplicates.php
```

---

## 🔄 Migrations (`migrations/`)

Scripts de migración de datos y estructura. Estos modifican la base de datos.

| Script | Descripción |
|--------|-------------|
| `add_*.php/sql` | Añaden campos o datos |
| `create_*.php/sql` | Crean tablas o estructura |
| `fix_*.php` | Corrigen datos |
| `update_*.php` | Actualizan datos existentes |
| `adductors.php` | Añade ejercicios de aductores |
| `organize_muscles.php` | Organiza grupos musculares |

**Uso:**
```bash
php scripts/migrations/update_equipment_categories.php
```

> ⚠️ **Precaución**: Ejecutar solo si sabes lo que haces. Hacer backup antes.

---

## 🌱 Seeders (`seeders/`)

Scripts para insertar datos iniciales (ejercicios, equipamiento, etc.).

| Script | Descripción |
|--------|-------------|
| `insert_back_exercises.php` | Ejercicios de espalda |
| `insert_biceps_exercises.php` | Ejercicios de bíceps |
| `insert_crossfit_machines.php` | Máquinas de CrossFit |
| `insert_equipment.php` | Equipamiento general |
| `insert_exercises.php` | Ejercicios varios |
| `insert_leg_exercises.php` | Ejercicios de piernas |
| `insert_machines.php` | Máquinas de gimnasio |
| `insert_training_data.php` | Datos de entrenamientos |
| ... y más | |

**Uso:**
```bash
php scripts/seeders/insert_exercises.php
```

---

## ⚠️ Legacy (`legacy/`)

Scripts obsoletos o duplicados. **No usar** a menos que sepas exactamente qué hacen.

---

## 📝 Notas Importantes

1. **Backup**: Siempre hacer backup de la base de datos antes de ejecutar scripts de migración
2. **Entorno**: Algunos scripts asumen entorno de desarrollo (`APP_ENV=dev`)
3. **Dependencias**: Algunos scripts pueden requerir la aplicación Symfony cargada
4. **One-off**: La mayoría son scripts de una sola ejecución

---

## 🆕 Crear Nuevos Scripts

Si necesitas crear un nuevo script:

1. Colócalo en la carpeta apropiada según su función
2. Añade comentario de cabecera:
```php
<?php
/**
 * Script: [nombre]
 * Descripción: [qué hace]
 * Uso: php scripts/[carpeta]/[nombre].php
 * Fecha: [YYYY-MM-DD]
 */
```
3. Documenta el script en este README
