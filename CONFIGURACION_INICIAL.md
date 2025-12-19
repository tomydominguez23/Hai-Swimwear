# 🔧 Configuración Inicial - Base de Datos Supabase

## 📋 Pasos para Configurar la Conexión

### Opción 1: Script Interactivo (Recomendado) ⭐

1. **Abre el script de configuración:**
   ```
   http://localhost/database/setup_config.php
   ```

2. **Completa el formulario con tus datos de Supabase:**
   - **Host**: `db.xxxxxxxxxxxxx.supabase.co` (de tu proyecto Supabase)
   - **Base de datos**: `postgres` (generalmente)
   - **Usuario**: `postgres` (generalmente)
   - **Contraseña**: Tu contraseña de Supabase
   - **Puerto**: `5432`

3. **Haz clic en "Guardar Configuración"**

4. **El script probará la conexión automáticamente**

### Opción 2: Configuración Manual

1. **Obtén tus credenciales de Supabase:**
   - Ve a [Supabase Dashboard](https://app.supabase.com)
   - Selecciona tu proyecto
   - Ve a **Settings** > **Database**
   - Copia los siguientes datos:
     - Host (Connection string)
     - Database name
     - User
     - Password
     - Port

2. **Edita el archivo `database/config_supabase.php`:**
   ```php
   define('SUPABASE_HOST', 'db.xxxxxxxxxxxxx.supabase.co'); // Tu host aquí
   define('SUPABASE_DB', 'postgres'); // Tu base de datos
   define('SUPABASE_USER', 'postgres'); // Tu usuario
   define('SUPABASE_PASS', 'tu_contraseña_aqui'); // Tu contraseña
   define('SUPABASE_PORT', '5432'); // Tu puerto
   ```

3. **Guarda el archivo**

## 🧪 Probar la Conexión

Después de configurar, prueba la conexión:

```
http://localhost/database/test_connection.php
```

Deberías ver un mensaje de éxito si todo está bien.

## 📍 ¿Dónde encontrar los datos en Supabase?

1. Ve a [Supabase Dashboard](https://app.supabase.com)
2. Selecciona tu proyecto
3. Haz clic en **Settings** (⚙️) en el menú lateral
4. Selecciona **Database**
5. En la sección **Connection string** o **Connection info** encontrarás:
   - **Host**: `db.xxxxxxxxxxxxx.supabase.co`
   - **Database**: `postgres`
   - **User**: `postgres`
   - **Password**: (la que configuraste al crear el proyecto)
   - **Port**: `5432`

## ✅ Verificación

Una vez configurado, deberías poder:

1. ✅ Probar conexión: `database/test_connection.php`
2. ✅ Iniciar sesión en el panel: `admin/login.php`
3. ✅ Ver estadísticas en el dashboard
4. ✅ Gestionar productos desde la base de datos

## 🔒 Seguridad

- **NO** subas `config_supabase.php` a Git
- Mantén tus credenciales seguras
- Usa variables de entorno en producción si es posible

## 🆘 ¿Problemas?

### Error: "Connection refused"
- Verifica que el host sea correcto
- Asegúrate de que tu proyecto Supabase esté activo

### Error: "password authentication failed"
- Verifica que la contraseña sea correcta
- Puedes resetearla en Supabase Dashboard > Settings > Database

### Error: "relation does not exist"
- Necesitas importar el schema SQL
- Ve a Supabase > SQL Editor y ejecuta `schema_postgresql.sql`

## 📞 Siguiente Paso

Una vez configurado, puedes:
1. Probar la conexión
2. Acceder al panel de administración
3. Comenzar a gestionar productos

