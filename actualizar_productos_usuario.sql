-- Script para actualizar la tabla productos
-- Añadir campos para identificar productos del usuario vs productos de prueba

-- 1. Agregar campo creado_por (referencia al usuario que creó el producto)
ALTER TABLE productos ADD COLUMN IF NOT EXISTS creado_por INT NULL AFTER estado;

-- 2. Agregar campo es_prueba (para marcar productos de prueba/ejemplo)
ALTER TABLE productos ADD COLUMN IF NOT EXISTS es_prueba TINYINT(1) DEFAULT 0 AFTER creado_por;

-- 3. Agregar índices para mejorar el rendimiento
ALTER TABLE productos ADD INDEX IF NOT EXISTS idx_creado_por (creado_por);
ALTER TABLE productos ADD INDEX IF NOT EXISTS idx_es_prueba (es_prueba);

-- 4. Marcar productos existentes como prueba (opcional, descomentar si quieres marcar todos los actuales como prueba)
-- UPDATE productos SET es_prueba = 1 WHERE creado_por IS NULL;

-- 5. Crear vista para productos del usuario (solo productos reales, no de prueba)
CREATE OR REPLACE VIEW productos_usuario AS
SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.es_prueba = 0;

-- 6. Crear vista para todos los productos (incluyendo prueba)
CREATE OR REPLACE VIEW productos_todos AS
SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug,
       CASE WHEN p.es_prueba = 1 THEN 'Prueba' ELSE 'Real' END as tipo_producto
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id;
