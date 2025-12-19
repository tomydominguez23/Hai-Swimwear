# 📘 Guía Completa: Vincular Web con phpMyAdmin

## 🎯 Objetivo
Conectar tu aplicación web **Hai Swimwear** con tu base de datos MySQL administrada por phpMyAdmin.

---

## 📋 PASO 1: Obtener las Credenciales de phpMyAdmin

### ¿Dónde encontrar tus credenciales?

1. **Abre phpMyAdmin** en tu navegador
2. Necesitarás estos datos:
   - **Servidor/Host**: Generalmente es `localhost` o la IP de tu servidor
   - **Usuario**: El usuario con el que accedes a phpMyAdmin (comúnmente `root`)
   - **Contraseña**: La contraseña de tu usuario MySQL
   - **Puerto**: Generalmente `3306` (el puerto por defecto de MySQL)
   - **Nombre de Base de Datos**: Si ya tienes una, anótala. Si no, la crearemos.

### Ejemplo de Credenciales Comunes:

**Si trabajas en local (XAMPP, WAMP, MAMP):**
```
Host: localhost
Usuario: root
Contraseña: (vacía) o la que configuraste
Puerto: 3306
Base de Datos: hai_swimwear
```

**Si trabajas en un servidor (hosting):**
```
Host: localhost o IP del servidor
Usuario: tu_usuario_mysql
Contraseña: tu_contraseña_mysql
Puerto: 3306
Base de Datos: nombre_de_tu_bd
```

---

## 🗄️ PASO 2: Crear la Base de Datos

### Opción A: Crear desde phpMyAdmin (Interfaz Visual)

1. **Abre phpMyAdmin**
2. Haz clic en **"Nueva"** o **"New"** en el panel izquierdo
3. Nombre de la base de datos: `hai_swimwear`
4. Cotejamiento: Selecciona `utf8mb4_unicode_ci`
5. Haz clic en **"Crear"**

### Opción B: Importar el Script SQL Completo (Recomendado)

1. **Abre phpMyAdmin**
2. Haz clic en la pestaña **"SQL"** en la parte superior
3. **Copia y pega** todo el contenido del archivo `SCHEMA_COMPLETO_MYSQL.sql`
4. Haz clic en **"Continuar"** o **"Go"**
5. ¡Listo! Se creará la base de datos con todas las tablas y datos iniciales

**Archivo a usar**: `SCHEMA_COMPLETO_MYSQL.sql` (está en la raíz de tu proyecto)

---

## ⚙️ PASO 3: Configurar la Conexión en tu Aplicación

### Editar el archivo `config.php`

**Ruta del archivo**: `/workspace/config.php`

Abre el archivo y actualiza estas líneas (8-12) con tus credenciales:

```php
// Configuración de la base de datos
define('DB_HOST', 'localhost');           // Tu servidor MySQL
define('DB_NAME', 'hai_swimwear');        // Nombre de tu base de datos
define('DB_USER', 'root');                // Tu usuario MySQL
define('DB_PASS', '');                    // Tu contraseña MySQL
define('DB_CHARSET', 'utf8mb4');
```

### Ejemplo con Hosting:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tudominio_haiswim');
define('DB_USER', 'tudominio_admin');
define('DB_PASS', 'tu_contraseña_segura');
define('DB_CHARSET', 'utf8mb4');
```

---

## 🔧 PASO 4: Probar la Conexión

### Ejecuta el Script de Prueba

1. Abre tu navegador
2. Ve a: `http://tu-dominio.com/test_connection_mysql.php`
   - O si es local: `http://localhost/test_connection_mysql.php`

3. Deberías ver un mensaje como:
   ```
   ✅ Conexión exitosa a MySQL
   Versión de MySQL: 8.0.30
   Base de datos: hai_swimwear
   ```

### Si ves errores:

#### ❌ Error: "Access denied for user..."
- **Solución**: Verifica usuario y contraseña en `config.php`

#### ❌ Error: "Unknown database..."
- **Solución**: La base de datos no existe. Créala en phpMyAdmin (Paso 2)

#### ❌ Error: "Can't connect to MySQL server..."
- **Solución**: Verifica que MySQL esté corriendo y el host sea correcto

---

## 🌐 PASO 5: Configurar tu Dominio

### Si tienes un dominio personalizado:

1. **Actualiza `APP_URL` en `config.php`**:

```php
define('APP_URL', 'https://tu-dominio.com');
```

2. **Verifica el archivo `.htaccess`** (ya incluido):
   - Asegúrate de que exista en la raíz de tu proyecto
   - Apache debe tener `mod_rewrite` activado

3. **Configura SSL (HTTPS)** si lo tienes:
   - Actualiza `APP_URL` con `https://`
   - Verifica certificados SSL en tu hosting

### Estructura de URLs:

```
https://tu-dominio.com/              → Tienda principal
https://tu-dominio.com/admin/        → Panel de administración
https://tu-dominio.com/api.php       → API REST
```

---

## 🔐 PASO 6: Acceder al Panel de Administración

### Credenciales por defecto:

```
URL: https://tu-dominio.com/admin/login.php
Email: admin@haiswimwear.com
Contraseña: admin123
```

**⚠️ IMPORTANTE: Cambia la contraseña inmediatamente después del primer acceso**

### Para cambiar la contraseña:

1. Accede al panel de administración
2. Ve a **Configuración** → **Usuarios**
3. Edita el usuario administrador
4. Establece una contraseña segura

---

## 📊 PASO 7: Verificar que Todo Funciona

### Lista de Verificación:

- [ ] Base de datos creada en phpMyAdmin
- [ ] Todas las tablas creadas correctamente
- [ ] Conexión a la base de datos exitosa
- [ ] Panel de administración accesible
- [ ] Puedes iniciar sesión como administrador
- [ ] Las páginas de la tienda se cargan correctamente

---

## 🚀 PASO 8: Configuración del Dominio en Hosting

### Si estás usando un hosting (cPanel, Plesk, etc.):

#### 1. **Subir Archivos al Servidor**:
   - Usa FTP (FileZilla) o el administrador de archivos del hosting
   - Sube todos los archivos a: `/public_html/` o `/htdocs/`

#### 2. **Crear Base de Datos en el Hosting**:
   - Panel de Control → MySQL Databases
   - Crea una nueva base de datos
   - Crea un usuario y asígnalo a la base de datos
   - Anota: nombre de BD, usuario y contraseña

#### 3. **Importar el Schema SQL**:
   - Accede a phpMyAdmin desde tu hosting
   - Selecciona tu base de datos
   - Ve a "Importar"
   - Sube el archivo `SCHEMA_COMPLETO_MYSQL.sql`

#### 4. **Configurar Permisos**:
   - Carpeta `uploads/`: Permisos 755 o 777
   - Archivos `.php`: Permisos 644

#### 5. **Configurar DNS del Dominio**:
   - Apunta los registros A a la IP de tu servidor
   - Espera propagación DNS (24-48 horas)

---

## 📁 Archivos Importantes

| Archivo | Descripción |
|---------|-------------|
| `config.php` | Configuración principal de la aplicación |
| `config_mysql.php` | Configuración específica para MySQL |
| `SCHEMA_COMPLETO_MYSQL.sql` | Script para crear la base de datos |
| `test_connection_mysql.php` | Script para probar la conexión |
| `index.php` | Página principal de la tienda |
| `admin/index.php` | Panel de administración |
| `.htaccess` | Configuración de Apache |

---

## ❓ Problemas Comunes y Soluciones

### 1. **Página en blanco o error 500**
   - Activa el reporte de errores en PHP
   - Revisa el archivo `error_log` de tu servidor
   - Verifica permisos de archivos

### 2. **CSS y JS no cargan**
   - Verifica que el archivo `.htaccess` exista
   - Comprueba la ruta `APP_URL` en `config.php`
   - Verifica permisos de archivos (644)

### 3. **No se conecta a la base de datos**
   - Verifica credenciales en `config.php`
   - Asegúrate de que MySQL esté corriendo
   - Verifica que el usuario tenga permisos

### 4. **Error "Table doesn't exist"**
   - Importa el schema SQL completo
   - Verifica que todas las tablas se crearon

---

## 📞 Soporte Adicional

Si necesitas ayuda adicional:

1. **Revisa los archivos de documentación**:
   - `LEEME_PRIMERO.md`
   - `INSTALACION.md`
   - `DIAGNOSTICO_COMPLETO.md`

2. **Ejecuta el diagnóstico**:
   - Abre `verificar_conexion.php` en tu navegador
   - Te mostrará el estado de tu configuración

---

## ✅ ¡Listo!

Si completaste todos los pasos, tu aplicación web debería estar funcionando correctamente con tu base de datos MySQL en phpMyAdmin.

**Próximos pasos recomendados**:
1. Cambiar contraseña de administrador
2. Añadir productos y categorías
3. Personalizar imágenes y contenido
4. Configurar métodos de pago
5. Configurar email para notificaciones

---

**Hai Swimwear** - Sistema de Gestión E-commerce
