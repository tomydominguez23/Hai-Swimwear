# 🔍 Verificación de Base de Datos - Supabase

## 📋 Pasos para Verificar

### 1. Verificar Usuario Admin

En Supabase, ve a **SQL Editor** y ejecuta:

```sql
SELECT id, nombre, email, rol, activo 
FROM usuarios 
WHERE email = 'admin@haiswimwear.com';
```

**Si no aparece ningún resultado o falta el email/password:**

Ejecuta el archivo `crear_usuario_admin.sql` o este código:

```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;

INSERT INTO usuarios (nombre, email, password, rol, activo)
VALUES (
    'Administrador',
    'admin@haiswimwear.com',
    crypt('admin123', gen_salt('bf')),
    'super_admin',
    true
)
ON CONFLICT (email) DO UPDATE SET
    password = crypt('admin123', gen_salt('bf')),
    rol = 'super_admin',
    activo = true;
```

### 2. Verificar Estructura de Tablas

Ejecuta en SQL Editor:

```sql
-- Ver columnas de usuarios
SELECT column_name, data_type 
FROM information_schema.columns
WHERE table_name = 'usuarios'
ORDER BY ordinal_position;
```

**Debe tener estas columnas:**
- ✅ id
- ✅ nombre
- ✅ email
- ✅ password
- ✅ rol
- ✅ activo
- ✅ ultimo_acceso
- ✅ fecha_creacion
- ✅ fecha_actualizacion

### 3. Verificar Categorías

```sql
SELECT * FROM categorias;
```

**Si está vacío, ejecuta:**

```sql
INSERT INTO categorias (nombre, slug, descripcion, orden)
VALUES
    ('Bikini', 'bikini', 'Bikinis de dos piezas', 1),
    ('Traje de Baño', 'traje-bano', 'Trajes de baño enteros', 2),
    ('Bikini Entero', 'bikini-entero', 'Bikinis de una pieza', 3),
    ('Accesorios', 'accesorios', 'Accesorios de playa', 4)
ON CONFLICT (slug) DO NOTHING;
```

### 4. Verificar Configuración

```sql
SELECT * FROM configuracion;
```

**Si está vacío, ejecuta:**

```sql
INSERT INTO configuracion (clave, valor, tipo, descripcion)
VALUES
    ('nombre_sitio', 'Hai Swimwear', 'texto', 'Nombre del sitio web'),
    ('email_contacto', 'contacto@haiswimwear.com', 'texto', 'Email de contacto'),
    ('telefono_contacto', '+56 9 1234 5678', 'texto', 'Teléfono de contacto')
ON CONFLICT (clave) DO NOTHING;
```

## 🚀 Script Completo de Verificación

Para verificar todo de una vez, ejecuta el archivo:
- `verificar_y_completar.sql` en el SQL Editor de Supabase

Este script:
1. ✅ Verifica la estructura de las tablas
2. ✅ Crea/actualiza el usuario admin
3. ✅ Crea categorías si no existen
4. ✅ Crea configuración inicial si no existe
5. ✅ Muestra un resumen de todo

## ⚠️ Problemas Comunes

### Problema: El usuario admin no tiene email

**Solución:** Ejecuta `crear_usuario_admin.sql`

### Problema: No puedo hacer login

**Verifica:**
1. Que el usuario exista: `SELECT * FROM usuarios WHERE email = 'admin@haiswimwear.com';`
2. Que tenga password: La columna `password` no debe estar NULL
3. Que esté activo: `activo = true`

### Problema: Falta la columna password en usuarios

**Solución:** Verifica que hayas ejecutado el schema completo. La columna debe existir según el schema.

## ✅ Checklist Final

- [ ] Tabla `usuarios` existe y tiene el usuario admin
- [ ] Usuario admin tiene email: `admin@haiswimwear.com`
- [ ] Usuario admin tiene password (no NULL)
- [ ] Usuario admin tiene rol: `super_admin`
- [ ] Usuario admin está activo: `activo = true`
- [ ] Tabla `categorias` tiene al menos 4 categorías
- [ ] Tabla `configuracion` tiene configuración inicial
- [ ] Tabla `productos` existe (puede estar vacía)
- [ ] Todas las demás tablas existen

## 🧪 Probar Login

Después de verificar todo:

1. Ve a: `http://localhost/admin/login.php`
2. Email: `admin@haiswimwear.com`
3. Contraseña: `admin123`

Si funciona, ¡todo está correcto! 🎉

