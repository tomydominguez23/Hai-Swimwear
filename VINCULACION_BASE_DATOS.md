# 🔗 VINCULACIÓN DE BASE DE DATOS - PASO A PASO

## 📋 INFORMACIÓN QUE YA TENEMOS

Basado en lo que me proporcionaste anteriormente:

- **Host:** `db.rvedynuxwfdbqwgkdgjg.supabase.co`
- **Database:** `postgres`
- **User:** `postgres`
- **Password:** `M7s5bjxY5F99arcu`
- **Port:** `5432`
- **Proyecto Supabase:** `rvedynuxwfdbqwgkdgjg`

---

## ✅ PASO 1: EJECUTAR EL SQL EN SUPABASE

1. **Abre tu proyecto en Supabase**
   - Ve a [https://supabase.com/dashboard](https://supabase.com/dashboard)
   - Selecciona tu proyecto "Hai Swimwear"

2. **Abre el SQL Editor**
   - En el menú lateral, haz clic en **"SQL Editor"**
   - O ve directamente a: `https://supabase.com/dashboard/project/rvedynuxwfdbqwgkdgjg/sql`

3. **Ejecuta el script completo**
   - Abre el archivo `database/SCHEMA_COMPLETO.sql` del ZIP
   - Copia TODO el contenido
   - Pégalo en el SQL Editor de Supabase
   - Haz clic en **"Run"** o presiona `Ctrl + Enter`

4. **Verifica que se ejecutó correctamente**
   - Deberías ver: "Success. No rows returned"
   - O un mensaje de éxito

---

## ✅ PASO 2: VERIFICAR QUE LAS TABLAS SE CREARON

1. **Ve a Database > Tables**
   - En el menú lateral, haz clic en **"Database"** > **"Tables"**

2. **Deberías ver estas tablas:**
   - ✅ `usuarios`
   - ✅ `categorias`
   - ✅ `productos`
   - ✅ `producto_imagenes`
   - ✅ `producto_atributos`
   - ✅ `clientes`
   - ✅ `pedidos`
   - ✅ `pedido_items`
   - ✅ `mensajes`
   - ✅ `cotizaciones`
   - ✅ `cotizacion_items`
   - ✅ `imagenes_web`
   - ✅ `configuracion`
   - ✅ `movimientos_inventario`
   - ✅ `ventas`

---

## ✅ PASO 3: CONFIGURAR EL ARCHIVO PHP

El archivo `database/config_supabase.php` **YA ESTÁ CONFIGURADO** con tus credenciales:

```php
define('SUPABASE_HOST', 'db.rvedynuxwfdbqwgkdgjg.supabase.co');
define('SUPABASE_DB', 'postgres');
define('SUPABASE_USER', 'postgres');
define('SUPABASE_PASS', 'M7s5bjxY5F99arcu');
define('SUPABASE_PORT', '5432');
```

**No necesitas cambiar nada** a menos que hayas cambiado tu contraseña.

---

## ✅ PASO 4: VERIFICAR LA CONEXIÓN

### Opción A: Desde el navegador

1. **Extrae el ZIP** en tu servidor local (XAMPP, WAMP, etc.)
2. **Abre en el navegador:**
   ```
   http://localhost/hai-swimwear/admin/test_connection.php
   ```

3. **Deberías ver:**
   ```
   ✅ Conexión exitosa a Supabase
   Versión: PostgreSQL x.x.x
   ```

### Opción B: Desde el panel de admin

1. **Abre el panel de admin:**
   ```
   http://localhost/hai-swimwear/admin/login.php
   ```

2. **Inicia sesión:**
   - Email: `admin@haiswimwear.com`
   - Password: `admin123`

3. **Si puedes iniciar sesión**, la conexión funciona ✅

---

## ✅ PASO 5: PROBAR LA API

1. **Abre en el navegador:**
   ```
   http://localhost/hai-swimwear/admin/api.php?action=test
   ```

2. **Deberías ver:**
   ```json
   {
     "success": true,
     "message": "API funcionando correctamente",
     "php_version": "7.4.x",
     "method": "GET",
     "action": "test"
   }
   ```

---

## 🐛 SI HAY ERRORES

### Error: "Error de conexión a la base de datos"

**Posibles causas:**
1. Las credenciales en `database/config_supabase.php` no coinciden
2. El firewall bloquea la conexión
3. Supabase está inactivo

**Solución:**
1. Verifica las credenciales en Supabase: **Settings > Database**
2. Verifica que el proyecto esté activo en Supabase
3. Prueba la conexión desde otro lugar

### Error: "No autenticado" al probar la API

**Causa:** La API requiere autenticación para la mayoría de acciones

**Solución:**
1. Primero inicia sesión en `admin/login.php`
2. Luego prueba la API desde el panel de admin

### Error: "Unexpected token '<', "<?php"..."

**Causa:** Estás abriendo archivos PHP directamente (file://)

**Solución:**
- **NO** abras `file:///D:/.../api.php` directamente
- **SÍ** usa `http://localhost/hai-swimwear/admin/api.php`

---

## 📝 CHECKLIST FINAL

- [ ] SQL ejecutado en Supabase sin errores
- [ ] Todas las tablas aparecen en Database > Tables
- [ ] `database/config_supabase.php` tiene las credenciales correctas
- [ ] Puedo acceder a `test_connection.php` y veo "Conexión exitosa"
- [ ] Puedo iniciar sesión en `admin/login.php`
- [ ] La API responde en `admin/api.php?action=test`
- [ ] Puedo ver el dashboard del admin después de iniciar sesión

---

## 🎉 ¡LISTO!

Si todos los pasos están completos, tu base de datos está vinculada y funcionando.

**Próximos pasos:**
1. Cambiar la contraseña del administrador
2. Agregar productos desde el panel de admin
3. Subir imágenes desde "Web Image Management"
4. Personalizar la configuración del sitio

---

## 📞 ¿NECESITAS AYUDA?

Si algo no funciona:
1. Revisa los logs de PHP
2. Revisa la consola del navegador (F12)
3. Verifica que Supabase esté activo
4. Prueba `test_connection.php` primero



