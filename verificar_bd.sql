-- Script para verificar que todo esté correcto en la base de datos
-- Ejecuta esto en el SQL Editor de Supabase

-- 1. Verificar que el usuario admin existe y tiene email y password
SELECT id, nombre, email, rol, activo 
FROM usuarios 
WHERE rol = 'super_admin';

-- 2. Verificar estructura de la tabla usuarios
SELECT column_name, data_type, is_nullable
FROM information_schema.columns
WHERE table_name = 'usuarios'
ORDER BY ordinal_position;

-- 3. Verificar que existan categorías
SELECT * FROM categorias;

-- 4. Verificar que exista configuración inicial
SELECT * FROM configuracion;

-- 5. Verificar estructura de productos
SELECT column_name, data_type
FROM information_schema.columns
WHERE table_name = 'productos'
ORDER BY ordinal_position;

-- 6. Contar registros en cada tabla
SELECT 
    'usuarios' as tabla, COUNT(*) as registros FROM usuarios
UNION ALL
SELECT 'categorias', COUNT(*) FROM categorias
UNION ALL
SELECT 'productos', COUNT(*) FROM productos
UNION ALL
SELECT 'configuracion', COUNT(*) FROM configuracion
UNION ALL
SELECT 'clientes', COUNT(*) FROM clientes
UNION ALL
SELECT 'pedidos', COUNT(*) FROM pedidos
UNION ALL
SELECT 'mensajes', COUNT(*) FROM mensajes;

