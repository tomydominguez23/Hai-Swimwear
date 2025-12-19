# 🔧 Solución de Errores - Panel de Administración

## ⚠️ Errores Comunes y Soluciones

### Error 1: "Unexpected token '<', "<?php"..." is not valid JSON

**Causa:** El servidor está devolviendo código PHP en lugar de JSON. Esto ocurre cuando:
- Estás abriendo `index.html` en lugar de `index.php`
- El servidor no está ejecutando PHP
- Hay un error en el PHP que hace que se muestre el código fuente

**Solución:**
1. **Asegúrate de abrir `index.php`, NO `index.html`**
   ```
   ✅ CORRECTO: http://localhost/admin/index.php
   ❌ INCORRECTO: http://localhost/admin/index.html
   ```

2. **Verifica que tengas un servidor PHP funcionando:**
   - XAMPP, WAMP, MAMP, o servidor local
   - El archivo debe ejecutarse como PHP, no como HTML estático

3. **Verifica que no haya errores de sintaxis en PHP:**
   - Abre `admin/api.php` directamente en el navegador
   - Deberías ver JSON, no código PHP

### Error 2: "405 Method Not Allowed"

**Causa:** El método HTTP (POST, GET, etc.) no está permitido para esa ruta.

**Solución:**
- Verifica que el método esté manejado en `api.php`
- Asegúrate de que el formulario use el método correcto
- Verifica que no haya restricciones en el servidor

### Error 3: "Failed to execute 'json' on 'Response'"

**Causa:** La respuesta no es JSON válido, probablemente hay un error PHP.

**Solución:**
1. Abre directamente `admin/api.php?action=productos` en el navegador
2. Deberías ver JSON. Si ves código PHP o errores, hay un problema
3. Revisa los logs de error de PHP

### Error 4: "No autenticado"

**Causa:** No has iniciado sesión o la sesión expiró.

**Solución:**
1. Ve a `admin/login.php`
2. Inicia sesión con:
   - Email: `admin@haiswimwear.com`
   - Contraseña: `admin123`

## ✅ Checklist de Verificación

### 1. Servidor PHP
- [ ] Tienes un servidor PHP funcionando (XAMPP, WAMP, etc.)
- [ ] Puedes acceder a archivos PHP
- [ ] Los archivos `.php` se ejecutan, no se descargan

### 2. Archivos Correctos
- [ ] Estás usando `admin/index.php` (NO `index.html`)
- [ ] El archivo `admin/api.php` existe
- [ ] El archivo `database/config_supabase.php` existe y tiene tus credenciales

### 3. Base de Datos
- [ ] La conexión a Supabase funciona
- [ ] Has importado el schema SQL
- [ ] Existe la tabla `usuarios` con el usuario admin

### 4. Sesión
- [ ] Has iniciado sesión en `admin/login.php`
- [ ] La sesión está activa
- [ ] No hay errores de sesión en PHP

## 🧪 Pruebas Rápidas

### Probar Conexión a BD
```
http://localhost/database/test_connection.php
```

### Probar API directamente
```
http://localhost/admin/api.php?action=stats
```
Deberías ver JSON, no código PHP.

### Probar Login
```
http://localhost/admin/login.php
```

### Probar Panel (después de login)
```
http://localhost/admin/index.php
```

## 🔍 Debugging

### Ver errores de PHP
Agrega esto al inicio de `admin/api.php` (temporalmente):
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Ver qué está devolviendo la API
Abre la consola del navegador (F12) y revisa:
- Network tab: Ver las peticiones a `api.php`
- Response: Ver qué está devolviendo el servidor

### Verificar que PHP funciona
Crea un archivo `test.php`:
```php
<?php
phpinfo();
?>
```
Si ves información de PHP, está funcionando. Si ves código, no.

## 📝 Pasos para Solucionar

1. **Abre `admin/index.php` (NO index.html)**
2. **Inicia sesión en `admin/login.php`**
3. **Verifica la conexión: `database/test_connection.php`**
4. **Prueba la API: `admin/api.php?action=stats`**
5. **Revisa la consola del navegador (F12) para más detalles**

## 🆘 Si Nada Funciona

1. Verifica que tengas PHP instalado y funcionando
2. Verifica que el servidor web esté corriendo
3. Verifica que los archivos estén en la carpeta correcta
4. Revisa los logs de error de PHP
5. Asegúrate de haber importado el schema SQL en Supabase

