# 🔍 Diagnóstico de Problemas - Panel de Administración

## ❌ Error: "Unexpected token '<', "<?php"..." is not valid JSON

Este error significa que el servidor está devolviendo código PHP en lugar de ejecutarlo.

### 🔴 Causa Principal

**Estás abriendo `index.html` en lugar de `index.php`**

### ✅ Solución

1. **Cierra `index.html` si está abierto**
2. **Abre `admin/index.php` (NO index.html)**
3. **Asegúrate de iniciar sesión primero en `admin/login.php`**

## 🧪 Pruebas de Diagnóstico

### 1. Probar que PHP funciona

Abre en tu navegador:
```
http://localhost/admin/test_api.php
```

**Resultado esperado:** Deberías ver JSON, no código PHP.

**Si ves código PHP:**
- Tu servidor no está ejecutando PHP
- Verifica que tengas XAMPP/WAMP/MAMP corriendo
- Verifica que los archivos `.php` se ejecuten, no se descarguen

### 2. Probar la API directamente

Abre en tu navegador:
```
http://localhost/admin/api.php?action=test
```

**Resultado esperado:**
```json
{
  "success": true,
  "message": "API funcionando correctamente",
  "php_version": "8.x.x"
}
```

**Si ves código PHP:**
- El servidor no está ejecutando PHP
- Necesitas configurar un servidor web con PHP

### 3. Probar conexión a base de datos

Abre en tu navegador:
```
http://localhost/database/test_connection.php
```

**Resultado esperado:** Página con mensaje de éxito o error de conexión.

## 🔧 Soluciones por Problema

### Problema 1: "Veo código PHP en lugar de JSON"

**Causa:** No tienes servidor PHP funcionando o estás abriendo HTML.

**Solución:**
1. Instala XAMPP, WAMP o MAMP
2. Inicia Apache
3. Coloca tus archivos en `htdocs` (XAMPP) o `www` (WAMP)
4. Abre `http://localhost/admin/index.php` (NO index.html)

### Problema 2: "405 Method Not Allowed"

**Causa:** El método HTTP no está permitido.

**Solución:** Ya está corregido en `api.php`. Verifica que uses POST para crear productos.

### Problema 3: "Cannot read properties of null"

**Causa:** El formulario no se encuentra o los campos no tienen los atributos `name` correctos.

**Solución:** Ya está corregido en `script.js`. Verifica que el modal esté incluido.

### Problema 4: "No autenticado"

**Causa:** No has iniciado sesión o la sesión expiró.

**Solución:**
1. Ve a `admin/login.php`
2. Inicia sesión con:
   - Email: `admin@haiswimwear.com`
   - Contraseña: `admin123`

## 📋 Checklist de Verificación

### Servidor
- [ ] Tienes XAMPP/WAMP/MAMP instalado
- [ ] Apache está corriendo
- [ ] PHP está instalado (versión 7.4+)
- [ ] Los archivos están en la carpeta correcta (htdocs/www)

### Archivos
- [ ] Estás usando `admin/index.php` (NO index.html)
- [ ] El archivo `admin/api.php` existe
- [ ] El archivo `database/config_supabase.php` existe y tiene tus credenciales

### Base de Datos
- [ ] La conexión funciona (`test_connection.php`)
- [ ] Has importado el schema SQL
- [ ] Existe el usuario admin con email y password

### Sesión
- [ ] Has iniciado sesión en `admin/login.php`
- [ ] La sesión está activa

## 🚀 Pasos para Solucionar TODO

1. **Verifica que PHP funciona:**
   ```
   http://localhost/admin/test_api.php
   ```
   Debe mostrar JSON.

2. **Verifica la API:**
   ```
   http://localhost/admin/api.php?action=test
   ```
   Debe mostrar JSON con success: true.

3. **Inicia sesión:**
   ```
   http://localhost/admin/login.php
   ```
   Email: `admin@haiswimwear.com`
   Contraseña: `admin123`

4. **Abre el panel:**
   ```
   http://localhost/admin/index.php
   ```
   (NO index.html)

5. **Si aún hay errores:**
   - Abre la consola del navegador (F12)
   - Ve a la pestaña Network
   - Intenta cargar estadísticas
   - Haz clic en la petición a `api.php`
   - Mira la respuesta: debe ser JSON, no código PHP

## 🆘 Si Nada Funciona

1. **Verifica que tengas PHP instalado:**
   ```bash
   php -v
   ```

2. **Crea un archivo `test.php` simple:**
   ```php
   <?php
   echo "PHP funciona!";
   phpinfo();
   ?>
   ```
   Si ves información de PHP, funciona. Si ves código, no.

3. **Verifica la configuración del servidor:**
   - XAMPP: `http://localhost/phpinfo.php`
   - Debe mostrar información de PHP

## 📞 Información Necesaria

Si el problema persiste, necesito saber:
1. ¿Qué servidor usas? (XAMPP, WAMP, MAMP, otro)
2. ¿Qué ves cuando abres `admin/test_api.php`?
3. ¿Qué ves cuando abres `admin/api.php?action=test`?
4. ¿Estás usando `index.php` o `index.html`?

