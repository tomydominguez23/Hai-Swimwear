# 📋 INSTRUCCIONES PARA EJECUTAR SQL EN PHPMYADMIN

## ✅ PASO 1: CREAR LA BASE DE DATOS

1. **Abre phpMyAdmin**
   - Generalmente está en: `http://localhost/phpmyadmin`

2. **Crea la base de datos**
   - Haz clic en **"Nueva"** o **"New"** en el menú lateral
   - Nombre de la base de datos: `hai_swimwear`
   - Intercalación: `utf8mb4_unicode_ci`
   - Haz clic en **"Crear"** o **"Create"**

## ✅ PASO 2: EJECUTAR EL SQL

1. **Selecciona la base de datos**
   - En el menú lateral, haz clic en `hai_swimwear`

2. **Abre la pestaña SQL**
   - Haz clic en la pestaña **"SQL"** en la parte superior

3. **Copia y pega el SQL**
   - Abre el archivo: `database/SCHEMA_COMPLETO_MYSQL.sql`
   - Copia **TODO** el contenido (Ctrl+A, Ctrl+C)
   - Pégalo en el cuadro de texto de phpMyAdmin (Ctrl+V)

4. **Ejecuta el script**
   - Haz clic en el botón **"Continuar"** o **"Go"**
   - Espera a que termine la ejecución

5. **Verifica que funcionó**
   - Deberías ver un mensaje de éxito
   - En el menú lateral, deberías ver todas las tablas creadas:
     - ✅ usuarios
     - ✅ categorias
     - ✅ productos
     - ✅ producto_imagenes
     - ✅ producto_atributos
     - ✅ clientes
     - ✅ pedidos
     - ✅ pedido_items
     - ✅ mensajes
     - ✅ cotizaciones
     - ✅ cotizacion_items
     - ✅ imagenes_web
     - ✅ configuracion
     - ✅ movimientos_inventario
     - ✅ ventas

## ✅ PASO 3: CONFIGURAR LA CONEXIÓN PHP

1. **Edita el archivo de configuración**
   - Abre: `database/config_mysql.php`

2. **Actualiza las credenciales** (si es necesario):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'hai_swimwear');
   define('DB_USER', 'root');        // Tu usuario MySQL (generalmente 'root')
   define('DB_PASS', '');             // Tu contraseña MySQL (generalmente vacía en XAMPP)
   define('DB_PORT', '3306');
   ```

3. **Si usas XAMPP con contraseña:**
   - Si configuraste una contraseña para MySQL, ponla en `DB_PASS`
   - Si no configuraste contraseña, déjalo vacío `''`

## ✅ PASO 4: VERIFICAR LA CONEXIÓN

1. **Abre en el navegador:**
   ```
   http://localhost/hai-swimwear/admin/test_connection.php
   ```

2. **Deberías ver:**
   ```
   ✅ Conexión exitosa a MySQL
   Versión: MySQL x.x.x
   ```

## ✅ PASO 5: ACCEDER AL PANEL DE ADMIN

1. **Abre el panel de admin:**
   ```
   http://localhost/hai-swimwear/admin/login.php
   ```

2. **Credenciales por defecto:**
   - **Email:** `admin@haiswimwear.com`
   - **Password:** `admin123`

3. **Si puedes iniciar sesión**, ¡todo está funcionando! ✅

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Access denied for user 'root'@'localhost'"

**Solución:**
- Verifica que MySQL esté corriendo en XAMPP
- Verifica la contraseña en `config_mysql.php`
- Si no tienes contraseña, déjalo como `''` (vacío)

### Error: "Unknown database 'hai_swimwear'"

**Solución:**
- Asegúrate de crear la base de datos primero (Paso 1)
- Verifica que el nombre sea exactamente `hai_swimwear`

### Error: "Table already exists"

**Solución:**
- El script tiene `DROP TABLE IF EXISTS`, así que debería eliminar las tablas primero
- Si persiste, elimina manualmente las tablas en phpMyAdmin y vuelve a ejecutar el SQL

### Error: "Syntax error near..."

**Solución:**
- Asegúrate de usar `SCHEMA_COMPLETO_MYSQL.sql` (NO el de PostgreSQL)
- Verifica que copiaste TODO el contenido del archivo

---

## 📝 NOTAS IMPORTANTES

- **Usa el archivo correcto:** `SCHEMA_COMPLETO_MYSQL.sql` (NO el de PostgreSQL)
- **La contraseña del admin** está hasheada en el SQL, no necesitas cambiarla manualmente
- **Si cambias la contraseña del admin**, usa `password_hash()` de PHP para generar el hash

---

¡Listo! Tu base de datos MySQL está configurada. 🎉



