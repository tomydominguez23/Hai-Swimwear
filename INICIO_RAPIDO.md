# 🚀 INICIO RÁPIDO - Panel de Administración

## ✅ Todo está listo para usar

Tu panel de administración está completamente funcional. Aquí está todo lo que necesitas saber para empezar:

## 📋 Archivos Creados

### ✅ Nuevos Archivos
- `admin-script.js` - JavaScript del panel de administración
- `productos/` - Carpeta para páginas de productos
- `productos/ejemplo-bikini-azul.html` - Página de ejemplo

### ✅ Archivos Actualizados
- `admin.php` - Actualizado para cargar el nuevo script
- `styles.css` - Estilos completos del panel agregados
- `api.php` - Endpoint para crear páginas de productos

## 🎯 Cómo Empezar (3 Pasos)

### Paso 1: Acceder al Panel
```
http://tu-dominio.com/admin.php
```
- Inicia sesión con tus credenciales
- Verás el dashboard con estadísticas

### Paso 2: Ver el Ejemplo
```
http://tu-dominio.com/productos/ejemplo-bikini-azul.html
```
- Esta es una página de producto de ejemplo
- Así se verán todas las páginas que crees

### Paso 3: Agregar Tu Primer Producto

1. En el panel, haz clic en **"Productos"** en el menú izquierdo
2. Haz clic en **"+ Nuevo Producto"**
3. Completa el formulario:
   - Nombre: "Tu Producto"
   - Precio: 29990
   - Categoría: Selecciona una
   - Stock: 10
   - Descripción: Describe tu producto
4. Haz clic en **"Guardar Producto"**
5. ¡Listo! La página se creará automáticamente

## 🔧 Configuración Rápida

### Cambiar Número de WhatsApp

Edita `admin-script.js`, busca:
```javascript
window.open('https://wa.me/56912345678?text=' + message, '_blank');
```

Cambia `56912345678` por tu número (con código de país, sin +)

### Cambiar Precios de Envío

En `admin-script.js`, busca:
```html
Envío RM a $3.490 y REGIONES a $6.390
```

Modifica según tus tarifas.

## 📱 Funciones Principales

### Dashboard
- **Ubicación:** Primera pantalla al entrar
- **Función:** Ver estadísticas generales
- **Datos:** Productos, pedidos, clientes, ventas

### Productos
- **Ubicación:** Menú lateral → Productos
- **Función:** Gestionar todos tus productos
- **Acciones:** Crear, editar, eliminar, ver página

### Categorías
- **Ubicación:** Menú lateral → Categorías
- **Función:** Organizar productos por categoría
- **Acciones:** Crear, editar, ordenar

### Imágenes
- **Ubicación:** Menú lateral → Imágenes Web
- **Función:** Subir y gestionar imágenes
- **Acciones:** Subir, copiar URL, eliminar

## 🎨 Ejemplo de Producto Creado

Ya incluí un ejemplo completo en:
```
/productos/ejemplo-bikini-azul.html
```

**Características del ejemplo:**
- ✅ Diseño profesional
- ✅ Precio con descuento
- ✅ Botón de WhatsApp
- ✅ Indicador de stock
- ✅ Especificaciones completas
- ✅ Responsive (móvil y desktop)

## 🔍 Verifica que Todo Funciona

### Test 1: Panel de Administración
1. Abre: `http://tu-dominio.com/admin.php`
2. ¿Se ve el panel? ✅
3. ¿Aparecen los menús? ✅
4. ¿Puedes hacer clic en "Productos"? ✅

### Test 2: Página de Ejemplo
1. Abre: `http://tu-dominio.com/productos/ejemplo-bikini-azul.html`
2. ¿Se ve la página? ✅
3. ¿La imagen carga? ✅
4. ¿Los botones funcionan? ✅

### Test 3: Crear Producto
1. En el panel, ve a Productos
2. Haz clic en "+ Nuevo Producto"
3. ¿Se abre el modal? ✅
4. Completa el formulario y guarda
5. ¿Se crea la página automáticamente? ✅

## 📊 Estructura de URLs

### Panel de Administración
```
/admin.php                          # Panel principal
/admin.php#dashboard               # Dashboard
/admin.php#productos               # Gestión de productos
/admin.php#categorias              # Gestión de categorías
/admin.php#imagenes                # Gestión de imágenes
```

### Páginas de Productos
```
/productos/[nombre-producto].html  # Página individual
```

**Ejemplos:**
```
/productos/bikini-azul.html
/productos/traje-bano-negro.html
/productos/bikini-push-up-rosa.html
```

## 💡 Tips y Trucos

### 1. Slugs Automáticos
El sistema convierte automáticamente:
- "Bikini Azul Hai" → `bikini-azul-hai.html`
- "Traje de Baño" → `traje-de-bano.html`

### 2. Descuentos Automáticos
Si pones:
- Precio: 20990
- Precio Anterior: 29990
El sistema calcula el % de descuento automáticamente (-30%)

### 3. Stock en Tiempo Real
El indicador de stock cambia de color:
- 🟢 Verde: Stock normal (10+)
- 🟠 Naranja: Bajo stock (1-9)
- 🔴 Rojo: Agotado (0)

### 4. WhatsApp Directo
Al hacer clic en "Consultar":
- Se abre WhatsApp automáticamente
- El mensaje ya incluye el nombre del producto
- Solo falta que el cliente lo envíe

## 🐛 Solución Rápida de Problemas

### Problema: El panel no carga
**Solución:**
```bash
# Verifica que PHP esté funcionando
php -v

# Verifica permisos
chmod 755 admin.php
chmod 755 api.php
```

### Problema: No se crean las páginas
**Solución:**
```bash
# Verifica que la carpeta exista y tenga permisos
mkdir -p productos
chmod 755 productos
```

### Problema: Las imágenes no cargan
**Solución:**
```bash
# Crea y configura la carpeta de uploads
mkdir -p uploads
chmod 755 uploads
```

## 📞 Próximos Pasos

1. **Configura tu número de WhatsApp** (5 minutos)
2. **Crea tus categorías** (10 minutos)
3. **Agrega tus productos** (según cantidad)
4. **Sube imágenes de productos** (según cantidad)
5. **Prueba las páginas creadas** (5 minutos)

## ✅ Checklist de Configuración

- [ ] Cambié el número de WhatsApp
- [ ] Actualicé los precios de envío
- [ ] Creé mis categorías
- [ ] Agregué al menos un producto de prueba
- [ ] Verifiqué que la página del producto funciona
- [ ] Probé el botón de WhatsApp
- [ ] El diseño se ve bien en móvil
- [ ] Todas las estadísticas cargan correctamente

## 🎉 ¡Ya Estás Listo!

Todo está configurado y funcionando. Puedes empezar a:
- Agregar tus productos
- Gestionar tu inventario
- Ver estadísticas en tiempo real
- Crear páginas automáticamente

---

**¿Necesitas ayuda?** Revisa el archivo `PANEL_ADMIN_FUNCIONANDO.md` para documentación completa.

**Desarrollado para Hai Swimwear** 🌊👙
