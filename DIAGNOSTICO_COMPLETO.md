# 🔍 Diagnóstico Completo - Base de Datos

## 📋 Pasos para Diagnosticar

### 1. Probar Conexión a Base de Datos

Abre en tu navegador:
```
http://localhost/admin/test_connection.php
```

Este script verificará:
- ✅ Si existe el archivo de configuración
- ✅ Si las constantes están definidas
- ✅ Si la extensión PDO PostgreSQL está instalada
- ✅ Si la conexión funciona
- ✅ Si las tablas existen
- ✅ Si el usuario admin existe
- ✅ Si las funciones helper funcionan

### 2. Probar Endpoint de API

Abre en tu navegador:
```
http://localhost/admin/test_api_endpoint.php
```

Este script verificará:
- ✅ Si la API responde
- ✅ Si devuelve JSON válido
- ✅ Si el código HTTP es correcto

### 3. Probar Endpoint Test Directamente

Abre en tu navegador:
```
http://localhost/admin/api.php?action=test
```

**Debe mostrar:**
```json
{
  "success": true,
  "message": "API funcionando correctamente",
  "php_version": "8.x.x"
}
```

Si ves código PHP en lugar de JSON:
- ❌ El servidor no está ejecutando PHP
- ❌ Necesitas XAMPP/WAMP/MAMP corriendo

## 🔴 Errores Comunes y Soluciones

### Error: "Unexpected token '<', "<?php"..."

**Causa:** El servidor no está ejecutando PHP o estás abriendo HTML en lugar de PHP.

**Solución:**
1. Asegúrate de usar `index.php` (NO `index.html`)
2. Verifica que tengas un servidor PHP funcionando (XAMPP, WAMP, MAMP)
3. Abre `test_api.php` y verifica que muestre JSON

### Error: "No autenticado. Por favor, inicia sesión."

**Causa:** No has iniciado sesión o la sesión expiró.

**Solución:**
1. Ve a `admin/login.php`
2. Inicia sesión con:
   - Email: `admin@haiswimwear.com`
   - Contraseña: `admin123`

### Error: "Error de conexión a Supabase"

**Causa:** Problemas de conexión a la base de datos.

**Verificar:**
1. Abre `admin/test_connection.php`
2. Revisa qué prueba falla
3. Verifica las credenciales en `database/config_supabase.php`

### Error: "Tablas faltantes"

**Causa:** No has importado el schema SQL.

**Solución:**
1. Ve a Supabase > SQL Editor
2. Ejecuta el contenido de `database/schema_postgresql.sql`
3. Ejecuta el contenido de `database/verificar_y_completar.sql`

### Error: "Usuario admin NO encontrado"

**Causa:** El usuario admin no existe en la base de datos.

**Solución:**
1. Ve a Supabase > SQL Editor
2. Ejecuta el contenido de `database/crear_usuario_admin.sql`

### Error: "Extensión pdo_pgsql NO está cargada"

**Causa:** PHP no tiene la extensión PostgreSQL instalada.

**Solución:**
- **XAMPP:** Edita `php.ini` y descomenta `extension=pdo_pgsql`
- **WAMP:** Activa la extensión desde el menú
- **MAMP:** Similar a XAMPP

## 📊 Información que Necesito

Si el problema persiste, comparte:

1. **Resultado de `test_connection.php`:**
   - ¿Qué pruebas pasan?
   - ¿Qué pruebas fallan?
   - ¿Qué mensajes de error aparecen?

2. **Resultado de `test_api_endpoint.php`:**
   - ¿Qué código HTTP aparece?
   - ¿Qué respuesta muestra?

3. **Error en la consola del navegador:**
   - Abre F12 > Console
   - Copia el error exacto

4. **Configuración:**
   - ¿Qué servidor usas? (XAMPP, WAMP, MAMP, otro)
   - ¿Qué versión de PHP? (abre `test_api.php` para verlo)

## ✅ Checklist de Verificación

Antes de reportar un error, verifica:

- [ ] Tienes XAMPP/WAMP/MAMP instalado y corriendo
- [ ] Apache está activo
- [ ] Estás usando `index.php` (NO `index.html`)
- [ ] Has ejecutado `test_connection.php` y todas las pruebas pasan
- [ ] Has ejecutado `test_api_endpoint.php` y funciona
- [ ] Has iniciado sesión en `login.php`
- [ ] Has importado el schema SQL en Supabase
- [ ] Has creado el usuario admin en Supabase

## 🚀 Si Todo Falla

1. **Reinicia tu servidor** (Apache)
2. **Limpia la caché del navegador** (Ctrl+Shift+Delete)
3. **Verifica los logs de error:**
   - XAMPP: `xampp/apache/logs/error.log`
   - WAMP: Similar
4. **Comparte los resultados de los scripts de prueba**

