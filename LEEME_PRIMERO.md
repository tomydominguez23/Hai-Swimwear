# ⚠️ IMPORTANTE - LEE ESTO PRIMERO

## 🔴 PROBLEMA PRINCIPAL

El error **"Unexpected token '<', "<?php"..."** significa que estás recibiendo código PHP en lugar de JSON.

### ✅ SOLUCIÓN INMEDIATA

**NO uses `index.html` - USA `index.php`**

```
❌ INCORRECTO: http://localhost/admin/index.html
✅ CORRECTO:   http://localhost/admin/index.php
```

## 📋 Pasos para Solucionar

### 1. Verificar que PHP funciona

Abre en tu navegador:
```
http://localhost/admin/test_api.php
```

**Debe mostrar JSON**, no código PHP.

Si ves código PHP:
- No tienes servidor PHP funcionando
- Instala XAMPP, WAMP o MAMP
- Inicia Apache
- Coloca tus archivos en `htdocs` (XAMPP) o `www` (WAMP)

### 2. Probar la API

Abre:
```
http://localhost/admin/api.php?action=test
```

**Debe mostrar JSON:**
```json
{
  "success": true,
  "message": "API funcionando correctamente"
}
```

Si ves código PHP, el servidor no está ejecutando PHP.

### 3. Iniciar Sesión

**PRIMERO** inicia sesión:
```
http://localhost/admin/login.php
```

- Email: `admin@haiswimwear.com`
- Contraseña: `admin123`

### 4. Abrir el Panel

**DESPUÉS** de iniciar sesión, abre:
```
http://localhost/admin/index.php
```

**NO uses `index.html`**

## 🧪 Verificación Rápida

1. ✅ ¿Tienes XAMPP/WAMP/MAMP instalado y corriendo?
2. ✅ ¿Puedes abrir `test_api.php` y ver JSON?
3. ✅ ¿Puedes abrir `api.php?action=test` y ver JSON?
4. ✅ ¿Iniciaste sesión en `login.php`?
5. ✅ ¿Estás usando `index.php` (NO index.html)?

## 🆘 Si Aún No Funciona

### Verificar que PHP está instalado

Crea un archivo `test.php`:
```php
<?php
phpinfo();
?>
```

Si ves información de PHP, funciona. Si ves código, no.

### Verificar rutas

Asegúrate de que:
- Los archivos estén en `htdocs` (XAMPP) o `www` (WAMP)
- La URL sea `http://localhost/admin/index.php`
- No uses rutas de archivo (`file:///`)

## 📞 Información que Necesito

Si el problema persiste, dime:
1. ¿Qué servidor usas? (XAMPP, WAMP, MAMP, otro)
2. ¿Qué ves cuando abres `admin/test_api.php`?
3. ¿Qué ves cuando abres `admin/api.php?action=test`?
4. ¿Estás usando `index.php` o `index.html`?

## ✅ Resumen

**EL PROBLEMA:** Estás abriendo HTML en lugar de PHP, o PHP no está funcionando.

**LA SOLUCIÓN:** 
1. Usa `index.php` (NO index.html)
2. Asegúrate de tener servidor PHP funcionando
3. Inicia sesión primero en `login.php`

