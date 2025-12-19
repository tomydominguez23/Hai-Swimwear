# 🏖️ HAI SWIMWEAR - GUÍA DE INSTALACIÓN

## 📋 Requisitos Previos

- Servidor web con PHP 7.4 o superior (XAMPP, WAMP, o servidor local)
- PostgreSQL o cuenta de Supabase
- Navegador web moderno

---

## 🚀 PASO 1: CONFIGURAR LA BASE DE DATOS

### Opción A: Usando Supabase (Recomendado)

1. **Crear proyecto en Supabase**
   - Ve a [https://supabase.com](https://supabase.com)
   - Crea un nuevo proyecto
   - Anota tus credenciales

2. **Ejecutar el SQL**
   - Ve a tu proyecto en Supabase
   - Abre el **SQL Editor**
   - Copia y pega el contenido de `database/SCHEMA_COMPLETO.sql`
   - Ejecuta el script completo

3. **Obtener credenciales**
   - Ve a **Settings > Database**
   - Anota:
     - Host: `db.xxxxx.supabase.co`
     - Database: `postgres`
     - User: `postgres`
     - Password: (la contraseña de la base de datos)
     - Port: `5432`

### Opción B: PostgreSQL Local

1. **Instalar PostgreSQL**
   - Descarga e instala PostgreSQL desde [postgresql.org](https://www.postgresql.org/download/)

2. **Crear base de datos**
   ```sql
   CREATE DATABASE hai_swimwear;
   ```

3. **Ejecutar el SQL**
   - Abre `psql` o pgAdmin
   - Conecta a la base de datos `hai_swimwear`
   - Ejecuta `database/SCHEMA_COMPLETO.sql`

---

## 🔧 PASO 2: CONFIGURAR LA CONEXIÓN

1. **Editar archivo de configuración**
   - Abre `database/config_supabase.php`
   - Actualiza las siguientes líneas con tus credenciales:

```php
define('SUPABASE_HOST', 'db.xxxxx.supabase.co');
define('SUPABASE_DB', 'postgres');
define('SUPABASE_USER', 'postgres');
define('SUPABASE_PASS', 'TU_CONTRASEÑA_AQUI');
define('SUPABASE_PORT', '5432');
```

2. **Si usas PostgreSQL local**, edita `database/config_postgresql.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hai_swimwear');
define('DB_USER', 'postgres');
define('DB_PASS', 'TU_CONTRASEÑA');
define('DB_PORT', '5432');
```

---

## 📁 PASO 3: CONFIGURAR EL SERVIDOR

### Opción A: XAMPP (Windows)

1. **Copiar archivos**
   - Copia toda la carpeta del proyecto a `C:\xampp\htdocs\hai-swimwear\`

2. **Iniciar servicios**
   - Abre XAMPP Control Panel
   - Inicia **Apache** y **PostgreSQL** (si usas local)

3. **Acceder al sitio**
   - Frontend: `http://localhost/hai-swimwear/`
   - Admin: `http://localhost/hai-swimwear/admin/`

### Opción B: Servidor PHP Integrado

1. **Abrir terminal en la carpeta del proyecto**
2. **Iniciar servidor**
   ```bash
   php -S localhost:8000
   ```
3. **Acceder al sitio**
   - Frontend: `http://localhost:8000/`
   - Admin: `http://localhost:8000/admin/`

---

## 🔐 PASO 4: ACCEDER AL PANEL DE ADMINISTRACIÓN

1. **Ir a la página de login**
   - `http://localhost/hai-swimwear/admin/login.php`

2. **Credenciales por defecto**
   - **Email:** `admin@haiswimwear.com`
   - **Password:** `admin123`

3. **⚠️ IMPORTANTE:** Cambia la contraseña después del primer acceso

---

## ✅ PASO 5: VERIFICAR LA INSTALACIÓN

### Verificar conexión a la base de datos

1. **Abrir en el navegador:**
   - `http://localhost/hai-swimwear/admin/test_connection.php`

2. **Deberías ver:**
   ```
   ✅ Conexión exitosa a Supabase
   Versión: PostgreSQL x.x.x
   ```

### Verificar API

1. **Abrir en el navegador:**
   - `http://localhost/hai-swimwear/admin/api.php?action=test`

2. **Deberías ver:**
   ```json
   {
     "success": true,
     "message": "API funcionando correctamente"
   }
   ```

---

## 📂 ESTRUCTURA DE ARCHIVOS

```
hai-swimwear/
├── index.html              # Página principal (frontend)
├── styles.css              # Estilos del frontend
├── script.js               # JavaScript del frontend
├── admin/
│   ├── index.html          # Panel de administración
│   ├── login.php           # Página de login
│   ├── api.php             # API REST
│   ├── styles.css          # Estilos del admin
│   └── script.js           # JavaScript del admin
├── database/
│   ├── config_supabase.php # Configuración Supabase
│   └── SCHEMA_COMPLETO.sql # Script SQL completo
└── uploads/                # Carpeta para imágenes
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Unexpected token '<', "<?php"..."

**Causa:** Estás abriendo archivos PHP directamente en el navegador (file://)

**Solución:** 
- Asegúrate de usar un servidor web (XAMPP, WAMP, o `php -S localhost:8000`)
- Accede a través de `http://localhost/` no `file:///`

### Error: "Error de conexión a la base de datos"

**Causa:** Credenciales incorrectas o servidor no accesible

**Solución:**
1. Verifica las credenciales en `database/config_supabase.php`
2. Verifica que Supabase esté activo
3. Verifica que el firewall permita conexiones salientes

### Error: "No autenticado"

**Causa:** No has iniciado sesión

**Solución:**
1. Ve a `admin/login.php`
2. Inicia sesión con las credenciales por defecto

---

## 📝 NOTAS IMPORTANTES

- **Seguridad:** Cambia la contraseña del administrador después de la instalación
- **Producción:** No uses `admin123` en producción
- **SSL:** Supabase requiere SSL (`sslmode=require`)
- **Permisos:** Asegúrate de que la carpeta `uploads/` tenga permisos de escritura

---

## 🆘 SOPORTE

Si tienes problemas:
1. Revisa los logs de PHP (`php_error.log`)
2. Revisa la consola del navegador (F12)
3. Verifica la conexión con `admin/test_connection.php`

---

## ✅ CHECKLIST DE INSTALACIÓN

- [ ] Base de datos creada y SQL ejecutado
- [ ] Credenciales configuradas en `database/config_supabase.php`
- [ ] Servidor web funcionando
- [ ] Puedo acceder a `http://localhost/hai-swimwear/`
- [ ] Puedo acceder a `http://localhost/hai-swimwear/admin/login.php`
- [ ] Puedo iniciar sesión con las credenciales por defecto
- [ ] La conexión a la base de datos funciona (`test_connection.php`)
- [ ] La API funciona (`api.php?action=test`)

---

¡Listo! Tu tienda Hai Swimwear está instalada. 🎉



