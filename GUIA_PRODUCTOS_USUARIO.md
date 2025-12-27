# 🎯 Guía: Gestionar Productos del Usuario vs Productos de Prueba

## 📋 Resumen

Este sistema te permite:
- ✅ Mostrar solo **tus productos reales** en la página web pública
- 🧪 Ocultar **productos de prueba o demo** automáticamente
- 🗑️ Eliminar productos de prueba fácilmente
- 👤 Identificar quién creó cada producto

---

## 🚀 Paso a Paso para Configurar

### 1️⃣ Actualizar la Base de Datos

Primero, necesitas agregar los nuevos campos a tu tabla de productos:

**Opción A: Desde el navegador**
1. Abre el archivo: `http://localhost/gestionar_productos_prueba.php`
2. Busca la sección "Opciones Adicionales"
3. Haz clic en "✨ Añadir Campo" en la opción "📝 Agregar campo creado_por"

**Opción B: Desde phpMyAdmin**
1. Abre phpMyAdmin
2. Selecciona tu base de datos `hai_swimwear`
3. Ve a la pestaña "SQL"
4. Pega el contenido del archivo `actualizar_productos_usuario.sql`
5. Haz clic en "Continuar"

**Opción C: Desde MySQL CLI**
```bash
mysql -u root -p hai_swimwear < actualizar_productos_usuario.sql
```

### 2️⃣ Identificar y Eliminar Productos de Prueba

1. Abre en tu navegador: `http://localhost/gestionar_productos_prueba.php`

2. Verás una tabla con TODOS tus productos actuales, incluyendo:
   - 📸 Imagen del producto
   - 📝 Nombre y SKU
   - 🏷️ Categoría
   - 💰 Precio
   - 📦 Stock
   - 🎨 Estado

3. Para eliminar productos de prueba:
   - ✅ Marca las casillas de los productos que quieres eliminar
   - Puedes usar "☑️ Seleccionar Todos" para marcar todos
   - Haz clic en "🗑️ Eliminar Seleccionados"
   - Confirma la acción

4. **⚠️ IMPORTANTE**: La eliminación es permanente y también borrará:
   - Las imágenes físicas del producto
   - Los registros de imágenes en la base de datos
   - Las páginas HTML individuales del producto (si existen)

### 3️⃣ ¿Qué Pasa Ahora?

Después de eliminar los productos de prueba:

✅ **Página Web Pública (`index.html`, `productos.html`)**
- Solo mostrará productos con `es_prueba = 0` o `es_prueba IS NULL`
- Los visitantes NO verán productos de prueba

✅ **API Pública (`productos.php`)**
- Solo devuelve productos reales del usuario
- Productos de prueba quedan ocultos

✅ **Panel de Administración (`admin.php`)**
- Por defecto muestra todos los productos
- Puedes filtrar con `?incluir_prueba=0` para ver solo productos reales

✅ **Nuevos Productos**
- Se crean automáticamente con `es_prueba = 0` (productos reales)
- Aparecerán en la web pública inmediatamente

---

## 🔧 Opciones Avanzadas

### Marcar Productos Existentes como Prueba

Si tienes productos que quieres ocultar sin eliminar:

```sql
-- Marcar un producto específico como prueba
UPDATE productos SET es_prueba = 1 WHERE id = 123;

-- Marcar todos los productos sin creador como prueba
UPDATE productos SET es_prueba = 1 WHERE creado_por IS NULL;
```

### Eliminar TODOS los Productos

Si quieres empezar desde cero:

1. Ve a `gestionar_productos_prueba.php`
2. En "Opciones Adicionales", sección "🧪 Eliminar TODOS los productos"
3. Haz clic en "🗑️ Eliminar TODOS"
4. Confirma la acción (⚠️ NO se puede deshacer)

### Ver Productos de Prueba en el Panel Admin

Para ver todos los productos (incluidos los de prueba) en el panel:

```javascript
// En admin-script.js, al cargar productos:
fetch('api.php?action=productos&incluir_prueba=1')
```

---

## 📊 Estructura de los Nuevos Campos

### Campo: `creado_por`
- **Tipo**: INT (NULL)
- **Propósito**: ID del usuario que creó el producto
- **Uso futuro**: Para filtrar "Mis Productos" vs "Productos de Otros"

### Campo: `es_prueba`
- **Tipo**: TINYINT(1)
- **Valores**: 
  - `0` = Producto real del usuario ✅
  - `1` = Producto de prueba/demo 🧪
- **Default**: `0`

---

## ❓ Preguntas Frecuentes

### ¿Se eliminarán también las imágenes físicas?
✅ Sí, el script elimina automáticamente:
- Archivos de imagen del servidor
- Registros de imágenes en la BD
- Páginas HTML del producto

### ¿Puedo recuperar productos eliminados?
❌ No, la eliminación es permanente. Asegúrate de seleccionar solo los productos correctos.

### ¿Los productos nuevos se marcan automáticamente como reales?
✅ Sí, todos los productos nuevos se crean con `es_prueba = 0` por defecto.

### ¿Cómo sé cuáles son productos de prueba?
Los productos de prueba típicamente son:
- Productos con nombres genéricos ("Bikini Azul", "Traje Negro", etc.)
- Productos sin imágenes reales
- Productos con datos de ejemplo
- El producto `ejemplo-bikini-azul.html` en la carpeta productos/

### ¿Afecta esto a las categorías?
❌ No, las categorías no se ven afectadas. Solo se filtran productos.

---

## 🎉 ¡Listo!

Ahora tu sitio web solo mostrará tus productos reales. Los productos de prueba están ocultos o eliminados.

**Archivos Importantes:**
- 📄 `gestionar_productos_prueba.php` - Interfaz para gestionar productos
- 📄 `actualizar_productos_usuario.sql` - Script SQL de actualización
- 📄 `productos.php` - API pública (solo productos reales)
- 📄 `api.php` - API del panel admin

---

## 🆘 Soporte

Si tienes problemas:
1. Verifica que ejecutaste el script SQL correctamente
2. Revisa los permisos de la carpeta `uploads/`
3. Asegúrate de que la base de datos esté actualizada
4. Comprueba los logs de errores de PHP

---

**Fecha**: Diciembre 2025  
**Versión**: 1.0  
**Proyecto**: Hai Swimwear
