-- Script completo para verificar y completar lo que falta
-- Ejecuta esto en el SQL Editor de Supabase

-- ============================================
-- 1. VERIFICAR ESTRUCTURA DE TABLAS
-- ============================================

-- Verificar columnas de usuarios
SELECT column_name, data_type, is_nullable, column_default
FROM information_schema.columns
WHERE table_name = 'usuarios'
ORDER BY ordinal_position;

-- Verificar columnas de productos
SELECT column_name, data_type, is_nullable
FROM information_schema.columns
WHERE table_name = 'productos'
ORDER BY ordinal_position;

-- ============================================
-- 2. CREAR USUARIO ADMIN SI NO EXISTE
-- ============================================

-- Habilitar extensión para contraseñas
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Verificar si existe el usuario
SELECT * FROM usuarios WHERE email = 'admin@haiswimwear.com';

-- Si no existe, crearlo
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM usuarios WHERE email = 'admin@haiswimwear.com') THEN
        INSERT INTO usuarios (nombre, email, password, rol, activo)
        VALUES (
            'Administrador',
            'admin@haiswimwear.com',
            crypt('admin123', gen_salt('bf')),
            'super_admin',
            true
        );
        RAISE NOTICE 'Usuario admin creado';
    ELSE
        -- Actualizar password si ya existe
        UPDATE usuarios 
        SET password = crypt('admin123', gen_salt('bf')),
            rol = 'super_admin',
            activo = true,
            nombre = 'Administrador'
        WHERE email = 'admin@haiswimwear.com';
        RAISE NOTICE 'Usuario admin actualizado';
    END IF;
END $$;

-- ============================================
-- 3. VERIFICAR CATEGORÍAS
-- ============================================

SELECT * FROM categorias;

-- Si no hay categorías, crearlas
INSERT INTO categorias (nombre, slug, descripcion, orden)
VALUES
    ('Bikini', 'bikini', 'Bikinis de dos piezas', 1),
    ('Traje de Baño', 'traje-bano', 'Trajes de baño enteros', 2),
    ('Bikini Entero', 'bikini-entero', 'Bikinis de una pieza', 3),
    ('Accesorios', 'accesorios', 'Accesorios de playa', 4)
ON CONFLICT (slug) DO NOTHING;

-- ============================================
-- 4. VERIFICAR CONFIGURACIÓN
-- ============================================

SELECT * FROM configuracion;

-- Si no hay configuración, crearla
INSERT INTO configuracion (clave, valor, tipo, descripcion)
VALUES
    ('nombre_sitio', 'Hai Swimwear', 'texto', 'Nombre del sitio web'),
    ('email_contacto', 'contacto@haiswimwear.com', 'texto', 'Email de contacto'),
    ('telefono_contacto', '+56 9 1234 5678', 'texto', 'Teléfono de contacto'),
    ('color_primario', '#000000', 'texto', 'Color primario del sitio'),
    ('color_secundario', '#ffffff', 'texto', 'Color secundario del sitio'),
    ('envio_rm', '3490', 'numero', 'Costo de envío a Región Metropolitana'),
    ('envio_regiones', '6390', 'numero', 'Costo de envío a Regiones'),
    ('stock_minimo_alerta', '10', 'numero', 'Stock mínimo para alertas')
ON CONFLICT (clave) DO NOTHING;

-- ============================================
-- 5. RESUMEN FINAL
-- ============================================

SELECT 'RESUMEN DE VERIFICACIÓN' as titulo;

SELECT 
    'usuarios' as tabla, 
    COUNT(*) as total_registros,
    COUNT(CASE WHEN activo = true THEN 1 END) as activos
FROM usuarios
UNION ALL
SELECT 'categorias', COUNT(*), COUNT(*) FROM categorias
UNION ALL
SELECT 'productos', COUNT(*), COUNT(CASE WHEN estado = 'activo' THEN 1 END) FROM productos
UNION ALL
SELECT 'configuracion', COUNT(*), COUNT(*) FROM configuracion;

-- Verificar usuario admin
SELECT 
    'Usuario Admin' as verificación,
    CASE 
        WHEN EXISTS (SELECT 1 FROM usuarios WHERE email = 'admin@haiswimwear.com' AND activo = true) 
        THEN '✅ Existe y está activo'
        ELSE '❌ No existe o está inactivo'
    END as estado;

