# 🔑 Cómo Obtener la Contraseña de la Base de Datos

## ⚠️ Importante

La **API Key** que tienes es para usar la API REST de Supabase, pero para la conexión directa a PostgreSQL necesitas la **contraseña de la base de datos**, que es diferente.

## 📋 Pasos para Obtener la Contraseña

### Opción 1: Si ya la configuraste al crear el proyecto

1. Ve a [Supabase Dashboard](https://app.supabase.com)
2. Selecciona tu proyecto: **rvedynuxwfdbqwgkdgjg**
3. Ve a **Settings** (⚙️) > **Database**
4. Busca la sección **Database password**
5. Si la recuerdas, úsala directamente
6. Si no la recuerdas, ve a la Opción 2

### Opción 2: Resetear la Contraseña

1. Ve a [Supabase Dashboard](https://app.supabase.com)
2. Selecciona tu proyecto: **rvedynuxwfdbqwgkdgjg**
3. Ve a **Settings** (⚙️) > **Database**
4. Busca el botón **Reset database password** o **Change database password**
5. Haz clic en resetear
6. **Copia la nueva contraseña** (solo se muestra una vez)
7. Actualiza el archivo `config_supabase.php` con la nueva contraseña

### Opción 3: Usar el Script de Configuración

1. Abre: `http://localhost/database/setup_config.php`
2. Completa el formulario:
   - **Host**: `db.rvedynuxwfdbqwgkdgjg.supabase.co` ✅ (ya configurado)
   - **Base de datos**: `postgres` ✅
   - **Usuario**: `postgres` ✅
   - **Contraseña**: (la que obtuviste de Supabase)
   - **Puerto**: `5432` ✅
3. El script probará la conexión automáticamente

## 🔧 Configuración Actual

Tu configuración ya tiene:
- ✅ Host: `db.rvedynuxwfdbqwgkdgjg.supabase.co`
- ✅ Base de datos: `postgres`
- ✅ Usuario: `postgres`
- ⚠️ **Falta**: Contraseña de la base de datos

## 📝 Actualizar el Archivo

Edita `database/config_supabase.php` y reemplaza:

```php
define('SUPABASE_PASS', 'TU_CONTRASEÑA_DE_BD_AQUI');
```

Con tu contraseña real:

```php
define('SUPABASE_PASS', 'tu_contraseña_real_aqui');
```

## 🧪 Probar la Conexión

Después de actualizar la contraseña, prueba:

```
http://localhost/database/test_connection.php
```

## 💡 Nota sobre la API Key

La API Key que tienes (`eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`) es útil para:
- Usar la API REST de Supabase
- Autenticación en el frontend
- Llamadas desde JavaScript

Pero para la conexión directa a PostgreSQL (que es lo que necesitamos para PHP), se requiere la contraseña de la base de datos.

## 🆘 ¿No encuentras la contraseña?

Si no puedes encontrarla o resetearla:
1. Ve a Supabase Dashboard
2. Settings > Database
3. Busca "Connection string" o "Connection pooling"
4. Ahí deberías ver la contraseña o un botón para resetearla

