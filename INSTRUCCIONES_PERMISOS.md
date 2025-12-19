# 🔐 Instrucciones para Permisos de Base de Datos

## 📋 Pasos para Ejecutar los Permisos

### 1. Ve a Supabase SQL Editor

1. Abre [Supabase Dashboard](https://app.supabase.com)
2. Selecciona tu proyecto
3. Haz clic en **SQL Editor** en el menú lateral

### 2. Ejecuta el Script de Permisos

1. Abre el archivo: `database/permisos_supabase.sql`
2. Copia TODO el contenido
3. Pégalo en el SQL Editor de Supabase
4. Haz clic en **Run** o presiona `Ctrl+Enter`

### 3. Verifica que Funcionó

Deberías ver un mensaje de éxito. El script también mostrará una tabla con los permisos otorgados.

## ⚠️ Notas Importantes

- En Supabase, el usuario `postgres` generalmente ya tiene todos los permisos
- Este script es para **asegurar** que todo esté configurado correctamente
- Si usas **Row Level Security (RLS)**, puede que necesites políticas adicionales

## 🔍 Verificar Permisos

Después de ejecutar el script, puedes verificar los permisos ejecutando:

```sql
SELECT 
    table_name,
    privilege_type
FROM information_schema.table_privileges
WHERE grantee = 'postgres'
AND table_schema = 'public'
ORDER BY table_name, privilege_type;
```

## ✅ Si Todo Está Correcto

Después de ejecutar el script de permisos:
1. Las categorías deberían aparecer en el formulario de productos
2. Deberías poder crear productos sin errores
3. Deberías poder subir imágenes

