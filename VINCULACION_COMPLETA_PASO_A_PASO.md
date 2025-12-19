# 🔗 Vinculación Completa con Supabase - Paso a Paso

## 📋 Lo que Necesitamos de Supabase

### 1. Credenciales de Conexión a la Base de Datos

Necesitas estos 5 valores de Supabase:

1. **Host** (Servidor de base de datos)
2. **Database** (Nombre de la base de datos)
3. **User** (Usuario)
4. **Password** (Contraseña de la base de datos)
5. **Port** (Puerto)

---

## 🗺️ Dónde Encontrar Cada Dato en Supabase

### Paso 1: Ir a Settings > Database

1. Abre [Supabase Dashboard](https://app.supabase.com)
2. Selecciona tu proyecto: **rvedynuxwfdbqwgkdgjg**
3. En el menú lateral izquierdo, haz clic en **Settings** (⚙️)
4. Haz clic en **Database** en el submenú

### Paso 2: Obtener las Credenciales

En la página de Database Settings verás varias secciones:

#### Sección: "Connection string" o "Connection info"

Aquí encontrarás:

**1. Host:**
- Busca: **Host** o **DB Host**
- Ejemplo: `db.rvedynuxwfdbqwgkdgjg.supabase.co`
- ⚠️ NO confundas con "API URL"

**2. Database name:**
- Busca: **Database name** o **DB Name**
- Generalmente es: `postgres`
- ⚠️ NO confundas con el nombre del proyecto

**3. Port:**
- Busca: **Port**
- Generalmente es: `5432`
- ⚠️ Este es el puerto de PostgreSQL, no el de HTTP

**4. User:**
- Busca: **User** o **DB User**
- Generalmente es: `postgres`
- ⚠️ NO confundas con tu email de Supabase

**5. Password:**
- Busca: **Database password** o **DB Password**
- ⚠️ IMPORTANTE: Esta es la contraseña que configuraste al crear el proyecto
- Si no la recuerdas, haz clic en **Reset database password**
- ⚠️ NO confundas con la API Key

---

## 📝 Ejemplo de Dónde Está Cada Dato

En Supabase Dashboard > Settings > Database, verás algo como:

```
Connection string:
postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres

Connection info:
Host: db.xxxxx.supabase.co
Database name: postgres
Port: 5432
User: postgres
Password: [Click to reveal] o [Reset password]
```

---

## 🔧 Paso 3: Actualizar el Archivo de Configuración

Una vez que tengas todos los datos:

1. Abre el archivo: `database/config_supabase.php`
2. Actualiza estas líneas con TUS valores:

```php
define('SUPABASE_HOST', 'db.rvedynuxwfdbqwgkdgjg.supabase.co'); // ← Tu Host aquí
define('SUPABASE_DB', 'postgres'); // ← Tu Database name (generalmente postgres)
define('SUPABASE_USER', 'postgres'); // ← Tu User (generalmente postgres)
define('SUPABASE_PASS', 'TU_CONTRASEÑA_AQUI'); // ← Tu Password aquí
define('SUPABASE_PORT', '5432'); // ← Tu Port (generalmente 5432)
```

---

## ✅ Paso 4: Verificar la Conexión

Después de actualizar el archivo:

1. Abre en tu navegador:
   ```
   http://localhost/Pagina%20Hai%20definitiva/admin/verificar_que_falta.php
   ```

2. Debe mostrar:
   - ✅ Archivo de configuración existe
   - ✅ Todas las credenciales configuradas
   - ✅ Extensión PHP instalada
   - ✅ Conexión exitosa

---

## 🗄️ Paso 5: Crear las Tablas

Si aún no has creado las tablas:

1. Ve a Supabase > **SQL Editor**
2. Abre el archivo: `database/schema_postgresql.sql`
3. Copia TODO el contenido
4. Pégalo en el SQL Editor
5. Haz clic en **Run** o presiona `Ctrl+Enter`

---

## 👤 Paso 6: Crear Usuario Admin

1. Ve a Supabase > **SQL Editor**
2. Abre el archivo: `database/crear_usuario_admin.sql`
3. Copia TODO el contenido
4. Pégalo en el SQL Editor
5. Haz clic en **Run**

---

## 🔐 Paso 7: Dar Permisos

1. Ve a Supabase > **SQL Editor**
2. Abre el archivo: `database/permisos_supabase.sql`
3. Copia TODO el contenido
4. Pégalo en el SQL Editor
5. Haz clic en **Run**

---

## 📊 Resumen de lo que Necesitas

| Dato | Dónde Encontrarlo | Ejemplo |
|------|-------------------|---------|
| **Host** | Settings > Database > Connection info | `db.xxxxx.supabase.co` |
| **Database** | Settings > Database > Connection info | `postgres` |
| **User** | Settings > Database > Connection info | `postgres` |
| **Password** | Settings > Database > Database password | `tu_contraseña` |
| **Port** | Settings > Database > Connection info | `5432` |

---

## ⚠️ Errores Comunes

### "No encuentro la contraseña"
- Ve a Settings > Database
- Busca "Database password" o "Reset database password"
- Haz clic en resetear y copia la nueva contraseña

### "Confundo API Key con Password"
- **API Key**: Es para usar la API REST de Supabase (no la necesitamos ahora)
- **Database Password**: Es para conectar directamente a PostgreSQL (SÍ la necesitamos)

### "No encuentro el Host"
- Busca "Connection string" o "Connection info"
- El Host es la parte después de `@` y antes de `:`
- Ejemplo: `postgresql://postgres:pass@db.xxxxx.supabase.co:5432/postgres`
- El Host es: `db.xxxxx.supabase.co`

---

## 🆘 Si Aún No Funciona

Comparte conmigo:
1. ¿Qué valores tienes en Settings > Database?
2. ¿Qué error aparece en `verificar_que_falta.php`?
3. ¿Puedes ver la sección "Connection info" en Supabase?

