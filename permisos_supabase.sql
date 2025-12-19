-- ============================================
-- PERMISOS PARA LA BASE DE DATOS - SUPABASE
-- ============================================
-- Ejecuta este script en Supabase SQL Editor
-- Esto dará permisos necesarios para INSERT, UPDATE, DELETE

-- 1. Dar permisos al usuario postgres (ya debería tenerlos, pero por si acaso)
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO postgres;

-- 2. Dar permisos para tablas específicas
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE usuarios TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE productos TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE categorias TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE pedidos TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE pedido_items TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE clientes TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE mensajes TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE cotizaciones TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE cotizacion_items TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE imagenes_web TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE configuracion TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE producto_imagenes TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE producto_atributos TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE movimientos_inventario TO postgres;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE ventas TO postgres;

-- 3. Dar permisos en secuencias (para AUTO_INCREMENT/SERIAL)
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO postgres;

-- 4. Asegurar que las secuencias existan (si no existen, se crearán automáticamente)
-- Esto es importante para que los IDs se generen correctamente

-- 5. Habilitar Row Level Security (RLS) si es necesario, pero permitir acceso al usuario postgres
-- Por defecto, Supabase puede tener RLS habilitado, pero el usuario postgres debería poder acceder

-- 6. Verificar permisos (esto mostrará los permisos actuales)
SELECT 
    table_name,
    privilege_type
FROM information_schema.table_privileges
WHERE grantee = 'postgres'
AND table_schema = 'public'
ORDER BY table_name, privilege_type;

-- ============================================
-- NOTAS IMPORTANTES:
-- ============================================
-- 1. En Supabase, el usuario 'postgres' generalmente ya tiene todos los permisos
-- 2. Si usas Row Level Security (RLS), necesitarás políticas adicionales
-- 3. Este script es para asegurar que todo esté configurado correctamente
-- ============================================

