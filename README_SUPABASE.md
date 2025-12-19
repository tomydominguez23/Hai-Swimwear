# Configuración de Supabase para Hai Swimwear

## ¿Qué es Supabase?

Supabase es una plataforma que proporciona una base de datos PostgreSQL alojada en la nube, similar a Firebase pero con PostgreSQL.

## Pasos de Configuración

### 1. Obtener Credenciales de Supabase

1. Ve a tu proyecto en [Supabase Dashboard](https://app.supabase.com)
2. Ve a **Settings** > **Database**
3. Encuentra la sección **Connection string** o **Connection pooling**
4. Copia los siguientes valores:
   - **Host**: `db.xxxxxxxxxxxxx.supabase.co`
   - **Database name**: Generalmente es `postgres`
   - **Port**: `5432`
   - **User**: Generalmente es `postgres`
   - **Password**: Tu contraseña de base de datos

### 2. Configurar el Archivo de Conexión

Edita el archivo `database/config_supabase.php` y actualiza estas constantes:

```php
define('SUPABASE_HOST', 'db.xxxxxxxxxxxxx.supabase.co'); // Tu host de Supabase
define('SUPABASE_DB', 'postgres'); // Generalmente es 'postgres'
define('SUPABASE_USER', 'postgres'); // Tu usuario
define('SUPABASE_PASS', 'tu_contraseña_aqui'); // Tu contraseña
define('SUPABASE_PORT', '5432');
```

### 3. Importar el Schema SQL

#### Opción A: Desde el Dashboard de Supabase

1. Ve a tu proyecto en Supabase
2. Haz clic en **SQL Editor** en el menú lateral
3. Abre el archivo `database/schema_postgresql.sql`
4. Copia todo el contenido
5. Pégalo en el SQL Editor
6. Haz clic en **Run** o presiona `Ctrl+Enter`

#### Opción B: Desde la línea de comandos (psql)

```bash
psql "postgresql://postgres:[TU_PASSWORD]@db.xxxxxxxxxxxxx.supabase.co:5432/postgres" -f schema_postgresql.sql
```

### 4. Habilitar Extensión pgcrypto

En el SQL Editor de Supabase, ejecuta:

```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

Esto es necesario para el hash de contraseñas.

### 5. Probar la Conexión

Abre en tu navegador:
```
http://localhost/database/test_connection.php
```

O si estás en producción:
```
https://tudominio.com/database/test_connection.php
```

Deberías ver un mensaje de éxito si la conexión funciona.

## Configuración de Seguridad en Supabase

### Permitir Conexiones Externas

1. Ve a **Settings** > **Database**
2. En **Connection pooling**, verifica que esté habilitado
3. Para desarrollo local, puedes usar **Direct connection**
4. Para producción, usa **Connection pooling** (más seguro)

### IP Whitelist (Opcional)

Si necesitas restringir conexiones por IP:
1. Ve a **Settings** > **Database**
2. Busca **IP Whitelist** o **Network Restrictions**
3. Agrega las IPs permitidas

## Estructura de Archivos

```
database/
├── config_supabase.php      # Configuración para Supabase
├── schema_postgresql.sql     # Schema SQL (usar este para Supabase)
├── test_connection.php       # Script de prueba
└── README_SUPABASE.md        # Este archivo
```

## Uso en el Código

### En archivos PHP del panel admin:

```php
<?php
require_once '../database/config_supabase.php';

// Ahora puedes usar las funciones
$productos = fetchAll("SELECT * FROM productos WHERE estado = 'activo'");
?>
```

### En la API:

El archivo `admin/api.php` ya está configurado para detectar automáticamente si usas Supabase.

## Ejemplo de Consulta

```php
<?php
require_once 'database/config_supabase.php';

// Obtener productos
$productos = fetchAll("SELECT * FROM productos ORDER BY fecha_creacion DESC");

foreach ($productos as $producto) {
    echo $producto['nombre'] . " - $" . formatCurrency($producto['precio']) . "\n";
}
?>
```

## Solución de Problemas

### Error: "Connection refused"

- Verifica que el host y puerto sean correctos
- Asegúrate de que tu proyecto Supabase esté activo
- Verifica que no haya restricciones de red

### Error: "password authentication failed"

- Verifica que la contraseña en `config_supabase.php` sea correcta
- Puedes resetear la contraseña en Supabase Dashboard > Settings > Database

### Error: "relation does not exist"

- Asegúrate de haber importado el schema SQL
- Verifica que estés conectado a la base de datos correcta

### Error: "extension pgcrypto does not exist"

Ejecuta en el SQL Editor de Supabase:
```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

## Ventajas de Supabase

✅ Base de datos PostgreSQL completamente gestionada
✅ SSL/TLS por defecto
✅ Backups automáticos
✅ Escalable
✅ Panel de administración web
✅ API REST automática (opcional)
✅ Autenticación integrada (opcional)

## Próximos Pasos

1. ✅ Configurar credenciales
2. ✅ Importar schema
3. ✅ Probar conexión
4. 🔄 Conectar panel de administración
5. 🔄 Conectar página principal
6. 🔄 Implementar autenticación

## Recursos

- [Documentación de Supabase](https://supabase.com/docs)
- [Guía de PostgreSQL](https://www.postgresql.org/docs/)
- [PDO PostgreSQL](https://www.php.net/manual/es/ref.pdo-pgsql.php)

