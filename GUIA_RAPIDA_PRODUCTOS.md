# 🚀 Guía Rápida: Sistema de Productos con Imágenes

## ✅ PROBLEMA RESUELTO

### 1️⃣ Imágenes al subir productos → FUNCIONANDO ✓
### 2️⃣ Página específica de productos → CREADA ✓

---

## 📸 Cómo Subir Productos con Imágenes

### Paso a Paso:

1. **Acceder al Panel Admin**
   ```
   Ir a: admin.php
   ```

2. **Crear Nuevo Producto**
   - Click en botón "Nuevo Producto"
   - Llenar el formulario:
     - Nombre (requerido)
     - Precio (requerido)
     - Categoría (requerido)
     - Stock, SKU, descripción, etc.

3. **Subir Imágenes** 👈 IMPORTANTE
   - Scroll hasta "Imágenes del Producto"
   - Click en el área o arrastra archivos
   - Puedes seleccionar múltiples imágenes
   - La primera imagen será la imagen principal

4. **Guardar**
   - Click en "Guardar Producto"
   - El sistema automáticamente:
     - ✓ Crea el producto
     - ✓ Sube las imágenes
     - ✓ Crea la página individual
     - ✓ Asocia todo en la base de datos

---

## 🛍️ Página de Productos

### Acceso:

- **Desde el menú:** Click en "Productos" en la navegación
- **URL directa:** `productos.html`
- **Desde el hero:** Click en "COMPRAR AHORA", "VER COLECCIÓN", etc.

### Características:

✨ **Carga Dinámica**
- Los productos se cargan automáticamente desde la base de datos
- Muestra imágenes reales de los productos
- Calcula descuentos automáticamente

🔍 **Búsqueda y Filtros**
- Barra de búsqueda en tiempo real
- Filtro por rango de precios
- Filtro por disponibilidad/descuentos

📊 **Ordenamiento**
- Más reciente
- Precio (menor a mayor / mayor a menor)
- Nombre (A-Z / Z-A)

📱 **Responsivo**
- Funciona perfectamente en móviles
- Sidebar de filtros adaptable
- Grid de productos flexible

---

## 📂 Estructura de Archivos

```
/workspace
├── index.html                    ← Página principal
├── productos.html               ← 🆕 NUEVA página de productos
├── admin.php                    ← Panel de administración
├── api.php                      ← 🔧 API actualizada con upload de imágenes
├── admin-script.js              ← 🔧 Script actualizado con subida de imágenes
├── modals.php                   ← Modales del admin
├── uploads/                     ← 🆕 NUEVO directorio para imágenes
│   └── productos/               ← Imágenes de productos
└── productos/                   ← Páginas individuales de productos
    └── ejemplo-bikini-azul.html
```

---

## 🔧 Endpoints API

### Obtener Productos
```
GET /api.php?action=productos&estado=activo
```

### Crear Producto
```
POST /api.php?action=productos
Content-Type: application/json

{
  "nombre": "Bikini Azul",
  "precio": 29990,
  "stock": 10,
  ...
}
```

### Subir Imágenes
```
POST /api.php?action=upload_product_images
Content-Type: multipart/form-data

FormData:
- product_id: 123
- imagenes[]: [archivo1.jpg, archivo2.jpg, ...]
- alt_text[]: ["Imagen 1", "Imagen 2", ...]
```

---

## 🎯 Flujo Completo

```
Usuario Admin
    ↓
Completa formulario de producto
    ↓
Selecciona 3 imágenes
    ↓
Click "Guardar"
    ↓
┌─────────────────────────────────┐
│  1. Crear producto en BD        │
│  2. Subir imagen1.jpg           │
│  3. Subir imagen2.jpg           │
│  4. Subir imagen3.jpg           │
│  5. Guardar URLs en DB          │
│  6. Crear página HTML producto  │
│  7. Actualizar lista admin      │
└─────────────────────────────────┘
    ↓
✓ Producto visible en productos.html
✓ Con imágenes reales
✓ Con página individual
```

---

## 🧪 Cómo Probar

### Test 1: Subir Producto con Imágenes

1. Acceder a `admin.php`
2. Click "Nuevo Producto"
3. Nombre: "Test Bikini"
4. Precio: 25000
5. Seleccionar 2-3 imágenes de prueba
6. Guardar
7. ✓ Verificar que aparece en la tabla
8. ✓ Verificar que tiene thumbnail

### Test 2: Ver Productos en Página

1. Acceder a `productos.html`
2. ✓ Verificar que carga el producto "Test Bikini"
3. ✓ Verificar que muestra las imágenes
4. ✓ Verificar que muestra el precio
5. Click en el producto
6. ✓ Debe abrir la página individual

### Test 3: Filtros y Búsqueda

1. En `productos.html`
2. Escribir "bikini" en la búsqueda
3. ✓ Debe filtrar productos
4. Seleccionar un rango de precio
5. ✓ Debe filtrar por precio
6. Cambiar ordenamiento
7. ✓ Debe reordenar productos

---

## ⚠️ Solución de Problemas

### Problema: "No se ven las imágenes"

**Solución:**
```bash
# Verificar permisos
chmod 755 /workspace/uploads
chmod 755 /workspace/uploads/productos

# Verificar que se subieron
ls -la /workspace/uploads/productos/
```

### Problema: "productos.html no carga productos"

**Verificar:**
1. Abrir consola del navegador (F12)
2. Ver errores en la pestaña "Console"
3. Verificar respuesta API en "Network"
4. Verificar que hay productos activos en la BD

### Problema: "Error al subir imágenes"

**Verificar:**
1. Tamaño de imagen < 5MB
2. Formato: JPG, PNG, WebP o GIF
3. Permisos del directorio uploads/
4. Espacio en disco disponible

---

## 📊 Tabla de Compatibilidad

| Característica | Estado |
|---------------|--------|
| Subida de imágenes | ✅ |
| Múltiples imágenes por producto | ✅ |
| Visualización en admin | ✅ |
| Visualización en productos.html | ✅ |
| Página individual de producto | ✅ |
| Filtros de productos | ✅ |
| Búsqueda de productos | ✅ |
| Ordenamiento | ✅ |
| Responsive | ✅ |
| Calculo de descuentos | ✅ |

---

## 🎨 Capturas del Sistema

### Panel Admin - Subida de Imágenes
```
┌─────────────────────────────────────┐
│  Nuevo Producto                  [X]│
├─────────────────────────────────────┤
│  Nombre: ________________            │
│  SKU: ________________               │
│  Precio: ________________            │
│                                      │
│  📸 Imágenes del Producto           │
│  ┌─────────────────────────────┐    │
│  │  📁 Arrastra o click aquí   │    │
│  │  Puedes subir múltiples     │    │
│  └─────────────────────────────┘    │
│                                      │
│  Preview:                            │
│  [img1] [img2] [img3]               │
│                                      │
│  [Cancelar]  [Guardar Producto]     │
└─────────────────────────────────────┘
```

### productos.html
```
┌─────────────────────────────────────┐
│     🏊‍♀️ Hai Swimwear                │
├─────────────────────────────────────┤
│ Swimwear by HAi      (24 Productos) │
├──────────┬──────────────────────────┤
│          │  [Ordenar Por ▼]         │
│ FILTROS  │                          │
│          │  ┌────┐ ┌────┐ ┌────┐   │
│ □ Bikini │  │img1│ │img2│ │img3│   │
│ □ Enterizo│  │25k │ │30k │ │20k │   │
│          │  └────┘ └────┘ └────┘   │
│ Precio   │  ┌────┐ ┌────┐ ┌────┐   │
│ □ <20k   │  │img4│ │img5│ │img6│   │
│ □ 20-30k │  └────┘ └────┘ └────┘   │
│          │                          │
└──────────┴──────────────────────────┘
```

---

## 🚀 Siguiente Nivel (Opcional)

Mejoras futuras que podrías implementar:

- [ ] Drag & drop para reordenar imágenes
- [ ] Editor de imágenes (crop, resize)
- [ ] Compresión automática de imágenes
- [ ] Múltiples vistas del producto (360°)
- [ ] Zoom en imágenes
- [ ] Lazy loading de imágenes
- [ ] CDN para imágenes
- [ ] Carrito de compras
- [ ] Pasarela de pago

---

## 📞 Soporte

Si necesitas ayuda adicional:

1. **Revisar logs del servidor**
2. **Ver consola del navegador (F12)**
3. **Verificar base de datos**
4. **Leer el archivo:** `SOLUCION_IMAGENES_Y_PRODUCTOS.md`

---

✨ **¡Sistema listo para usar!** ✨

Fecha: 19 de Diciembre, 2025
Proyecto: Hai Swimwear
