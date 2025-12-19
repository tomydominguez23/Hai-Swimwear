# Base de Datos - Hai Swimwear (PostgreSQL)

## Instalación para PostgreSQL

### 1. Requisitos Previos

- **PostgreSQL**: Versión 12 o superior
- **PHP**: Versión 7.4 o superior
- **Extensiones PHP requeridas**:
  - PDO
  - PDO_PGSQL
  - mbstring
  - json

### 2. Crear la Base de Datos

Desde la línea de comandos de PostgreSQL:

```bash
psql -U postgres
```

Luego ejecuta:

```sql
CREATE DATABASE hai_swimwear;
\c hai_swimwear
```

### 3. Importar el Schema

Opción 1: Desde la línea de comandos
```bash
psql -U postgres -d hai_swimwear -f schema_postgresql.sql
```

Opción 2: Desde pgAdmin
1. Abre pgAdmin
2. Conéctate al servidor PostgreSQL
3. Crea la base de datos `hai_swimwear`
4. Haz clic derecho en la base de datos → Query Tool
5. Abre y ejecuta el archivo `schema_postgresql.sql`

### 4. Habilitar Extensión para Contraseñas

Para que funcione el hash de contraseñas, ejecuta:

```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

### 5. Configurar la Conexión

Edita el archivo `config_postgresql.php` y actualiza las siguientes constantes:

```php
define('DB_HOST', 'localhost');      // Host de PostgreSQL
define('DB_NAME', 'hai_swimwear');   // Nombre de la base de datos
define('DB_USER', 'postgres');       // Usuario de PostgreSQL
define('DB_PASS', 'tu_contraseña');  // Contraseña de PostgreSQL
define('DB_PORT', '5432');           // Puerto de PostgreSQL (por defecto 5432)
```

### 6. Crear Usuario de Base de Datos (Recomendado)

Para mayor seguridad, crea un usuario específico:

```sql
CREATE USER hai_user WITH PASSWORD 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON DATABASE hai_swimwear TO hai_user;
\c hai_swimwear
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO hai_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO hai_user;
```

Luego actualiza `config_postgresql.php`:
```php
define('DB_USER', 'hai_user');
define('DB_PASS', 'tu_contraseña_segura');
```

## Diferencias con MySQL

### Sintaxis de Auto-incremento

- **MySQL**: `INT AUTO_INCREMENT`
- **PostgreSQL**: `SERIAL` o `INT GENERATED ALWAYS AS IDENTITY`

### Tipos de Datos

- **MySQL**: `TINYINT(1)` para booleanos
- **PostgreSQL**: `BOOLEAN`

- **MySQL**: `ENUM('valor1', 'valor2')`
- **PostgreSQL**: `VARCHAR` con `CHECK` constraint

### ON DUPLICATE KEY UPDATE

- **MySQL**: `ON DUPLICATE KEY UPDATE`
- **PostgreSQL**: `ON CONFLICT (campo) DO UPDATE`

### Parámetros en Consultas

- **MySQL**: `?` (placeholders posicionales)
- **PostgreSQL**: `$1, $2, $3...` (placeholders numerados)

### Hash de Contraseñas

- **MySQL**: `password_hash()` de PHP
- **PostgreSQL**: Función `crypt()` con extensión `pgcrypto`

## Uso de la API PHP

### Ejemplo: Obtener Productos

```php
<?php
require_once 'database/config_postgresql.php';

// Obtener todos los productos activos
$productos = fetchAll("SELECT * FROM productos WHERE estado = 'activo' ORDER BY fecha_creacion DESC");

foreach ($productos as $producto) {
    echo $producto['nombre'] . " - " . formatCurrency($producto['precio']) . "\n";
}
?>
```

### Ejemplo: Insertar Producto

```php
<?php
require_once 'database/config_postgresql.php';

$sql = "INSERT INTO productos (nombre, sku, slug, categoria_id, precio, stock, estado) 
        VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING id";

$params = [
    'Bikini Soporte Máximo',
    'HAI-BIK-001',
    'bikini-soporte-maximo',
    1,
    29990,
    15,
    'activo'
];

$id = insertAndGetId($sql, $params);
echo "Producto creado con ID: " . $id;
?>
```

### Ejemplo: Autenticación

```php
<?php
require_once 'database/config_postgresql.php';

session_start();

if ($_POST['email'] && $_POST['password']) {
    $user = fetchOne("SELECT * FROM usuarios WHERE email = $1 AND activo = true", [$_POST['email']]);
    
    if ($user && verifyPassword($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['user_name'] = $user['nombre'];
        
        header('Location: index.php');
    } else {
        echo "Credenciales incorrectas";
    }
}
?>
```

## Backup

### Crear Backup

```bash
pg_dump -U postgres hai_swimwear > backup_$(date +%Y%m%d).sql
```

### Restaurar Backup

```bash
psql -U postgres hai_swimwear < backup_20240101.sql
```

## Solución de Problemas

### Error: "extension pgcrypto does not exist"

Ejecuta:
```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

### Error: "relation does not exist"

Asegúrate de estar conectado a la base de datos correcta:
```sql
\c hai_swimwear
```

### Error de permisos

Verifica que el usuario tenga permisos:
```sql
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO tu_usuario;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO tu_usuario;
```

