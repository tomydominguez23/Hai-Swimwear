# 🖥️ Cómo Abrir las Páginas en Local

## 📋 Opción 1: Con XAMPP (Recomendado)

### 1. Instalar XAMPP
- Descarga XAMPP desde: https://www.apachefriends.org/
- Instálalo en `C:\xampp` (o donde prefieras)

### 2. Colocar tus archivos
- Copia toda la carpeta del proyecto a: `C:\xampp\htdocs\`
- Por ejemplo: `C:\xampp\htdocs\Pagina Hai definitiva\`

### 3. Iniciar Apache
- Abre el Panel de Control de XAMPP
- Haz clic en "Start" en Apache
- Debe aparecer en verde

### 4. Abrir en el navegador
Abre tu navegador y ve a:

**Para probar la conexión:**
```
http://localhost/Pagina%20Hai%20definitiva/admin/test_connection.php
```

**Para probar la API:**
```
http://localhost/Pagina%20Hai%20definitiva/admin/api.php?action=test
```

**Para el login:**
```
http://localhost/Pagina%20Hai%20definitiva/admin/login.php
```

**Para el panel admin:**
```
http://localhost/Pagina%20Hai%20definitiva/admin/index.php
```

---

## 📋 Opción 2: Con WAMP

### 1. Instalar WAMP
- Descarga WAMP desde: https://www.wampserver.com/
- Instálalo

### 2. Colocar tus archivos
- Copia toda la carpeta del proyecto a: `C:\wamp64\www\`
- Por ejemplo: `C:\wamp64\www\Pagina Hai definitiva\`

### 3. Iniciar WAMP
- Abre WAMP
- Debe aparecer el icono en verde en la bandeja del sistema

### 4. Abrir en el navegador
Mismo formato que XAMPP, pero con la ruta de WAMP.

---

## 📋 Opción 3: Con PHP Built-in Server (Sin XAMPP/WAMP)

### 1. Abrir Terminal/PowerShell
- Ve a la carpeta del proyecto: `D:\Pagina Hai definitiva`

### 2. Iniciar servidor PHP
```bash
php -S localhost:8000
```

### 3. Abrir en el navegador
**Para probar la conexión:**
```
http://localhost:8000/admin/test_connection.php
```

**Para probar la API:**
```
http://localhost:8000/admin/api.php?action=test
```

**Para el login:**
```
http://localhost:8000/admin/login.php
```

**Para el panel admin:**
```
http://localhost:8000/admin/index.php
```

---

## 🔍 Verificar que Funciona

### Paso 1: Probar PHP
Abre en tu navegador:
```
http://localhost/Pagina%20Hai%20definitiva/admin/test_api.php
```

**Si ves JSON:** ✅ PHP funciona
**Si ves código PHP:** ❌ El servidor no está ejecutando PHP

### Paso 2: Probar Conexión a Base de Datos
Abre en tu navegador:
```
http://localhost/Pagina%20Hai%20definitiva/admin/test_connection.php
```

Este script te dirá exactamente qué está funcionando y qué no.

### Paso 3: Probar API
Abre en tu navegador:
```
http://localhost/Pagina%20Hai%20definitiva/admin/api.php?action=test
```

**Debe mostrar:**
```json
{
  "success": true,
  "message": "API funcionando correctamente"
}
```

---

## ⚠️ Problemas Comunes

### "No se puede acceder a este sitio"
- Verifica que Apache esté corriendo (XAMPP/WAMP)
- Verifica que la URL sea correcta
- Verifica que los archivos estén en la carpeta correcta

### "Veo código PHP en lugar de JSON"
- El servidor no está ejecutando PHP
- Verifica que Apache esté corriendo
- Verifica que PHP esté instalado

### "Error 404"
- Verifica la ruta exacta de tus archivos
- Verifica que el nombre de la carpeta sea correcto
- Si tu carpeta tiene espacios, usa `%20` en la URL

---

## 📝 Notas Importantes

1. **NO abras los archivos directamente** (doble clic en el archivo)
   - ❌ `file:///D:/Pagina Hai definitiva/admin/index.php` (NO funciona)
   - ✅ `http://localhost/...` (SÍ funciona)

2. **Usa siempre `index.php`**, NO `index.html`

3. **Inicia sesión primero** en `login.php` antes de abrir `index.php`

---

## 🚀 Resumen Rápido

1. **Instala XAMPP** (si no lo tienes)
2. **Copia tu proyecto** a `C:\xampp\htdocs\`
3. **Inicia Apache** en XAMPP
4. **Abre en el navegador:**
   - `http://localhost/Pagina%20Hai%20definitiva/admin/test_connection.php`
   - `http://localhost/Pagina%20Hai%20definitiva/admin/login.php`

¡Eso es todo! 🎉

