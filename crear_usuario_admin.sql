-- Script para crear/actualizar el usuario administrador
-- Ejecuta esto en el SQL Editor de Supabase

-- Verificar si existe el usuario admin
SELECT * FROM usuarios WHERE email = 'admin@haiswimwear.com';

-- Si no existe o no tiene password, ejecuta esto:
-- Primero, asegúrate de que la extensión pgcrypto esté habilitada
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Crear o actualizar el usuario admin
-- La contraseña es: admin123
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
    activo = true,
    nombre = 'Administrador';

-- Verificar que se creó correctamente
SELECT id, nombre, email, rol, activo FROM usuarios WHERE email = 'admin@haiswimwear.com';

