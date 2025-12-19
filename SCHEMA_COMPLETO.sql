-- ============================================
-- BASE DE DATOS COMPLETA PARA HAI SWIMWEAR
-- PostgreSQL / Supabase
-- ============================================
-- Ejecuta este script completo en Supabase SQL Editor
-- IMPORTANTE: Ejecuta todo el script de una vez
-- ============================================

-- Extensiones necesarias
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================
-- FUNCIONES
-- ============================================

-- Función para actualizar fecha_actualizacion automáticamente
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- ============================================
-- TABLAS (ORDEN CORRECTO - SIN FOREIGN KEYS PRIMERO)
-- ============================================

-- Tabla de Usuarios/Administradores
DROP TABLE IF EXISTS usuarios CASCADE;
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'admin' CHECK (rol IN ('admin', 'super_admin', 'editor')),
    activo BOOLEAN DEFAULT TRUE,
    ultimo_acceso TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_rol ON usuarios(rol);

-- Tabla de Categorías
DROP TABLE IF EXISTS categorias CASCADE;
CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT NULL,
    imagen VARCHAR(255) NULL,
    orden INTEGER DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_categorias_slug ON categorias(slug);
CREATE INDEX idx_categorias_activo ON categorias(activo);

CREATE TRIGGER update_categorias_updated_at BEFORE UPDATE ON categorias
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Productos (depende de categorias)
DROP TABLE IF EXISTS productos CASCADE;
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    sku VARCHAR(50) UNIQUE NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    categoria_id INTEGER NULL,
    subcategoria VARCHAR(100) NULL,
    descripcion_corta TEXT NULL,
    descripcion_larga TEXT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    precio_anterior DECIMAL(10, 2) NULL,
    stock INTEGER DEFAULT 0,
    stock_minimo INTEGER DEFAULT 5,
    peso DECIMAL(8, 2) NULL,
    dimensiones VARCHAR(100) NULL,
    incluye_iva BOOLEAN DEFAULT TRUE,
    producto_destacado BOOLEAN DEFAULT FALSE,
    estado VARCHAR(20) DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo', 'agotado')),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE INDEX idx_productos_sku ON productos(sku);
CREATE INDEX idx_productos_slug ON productos(slug);
CREATE INDEX idx_productos_categoria ON productos(categoria_id);
CREATE INDEX idx_productos_estado ON productos(estado);
CREATE INDEX idx_productos_destacado ON productos(producto_destacado);

CREATE TRIGGER update_productos_updated_at BEFORE UPDATE ON productos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Imágenes de Productos (depende de productos)
DROP TABLE IF EXISTS producto_imagenes CASCADE;
CREATE TABLE producto_imagenes (
    id SERIAL PRIMARY KEY,
    producto_id INTEGER NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INTEGER DEFAULT 0,
    es_principal BOOLEAN DEFAULT FALSE,
    alt_text VARCHAR(255) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE INDEX idx_producto_imagenes_producto ON producto_imagenes(producto_id);
CREATE INDEX idx_producto_imagenes_principal ON producto_imagenes(es_principal);

-- Tabla de Atributos de Productos (depende de productos)
DROP TABLE IF EXISTS producto_atributos CASCADE;
CREATE TABLE producto_atributos (
    id SERIAL PRIMARY KEY,
    producto_id INTEGER NOT NULL,
    tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('talla', 'color', 'soporte', 'material')),
    valor VARCHAR(100) NOT NULL,
    stock INTEGER DEFAULT 0,
    precio_adicional DECIMAL(10, 2) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE INDEX idx_producto_atributos_producto ON producto_atributos(producto_id);
CREATE INDEX idx_producto_atributos_tipo ON producto_atributos(tipo);

-- Tabla de Clientes
DROP TABLE IF EXISTS clientes CASCADE;
CREATE TABLE clientes (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20) NULL,
    direccion TEXT NULL,
    ciudad VARCHAR(100) NULL,
    region VARCHAR(100) NULL,
    codigo_postal VARCHAR(20) NULL,
    fecha_nacimiento DATE NULL,
    total_gastado DECIMAL(10, 2) DEFAULT 0,
    total_pedidos INTEGER DEFAULT 0,
    estado VARCHAR(20) DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo', 'bloqueado')),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_clientes_email ON clientes(email);
CREATE INDEX idx_clientes_estado ON clientes(estado);

CREATE TRIGGER update_clientes_updated_at BEFORE UPDATE ON clientes
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Pedidos (depende de clientes)
DROP TABLE IF EXISTS pedidos CASCADE;
CREATE TABLE pedidos (
    id SERIAL PRIMARY KEY,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INTEGER NULL,
    nombre_cliente VARCHAR(200) NOT NULL,
    email_cliente VARCHAR(100) NOT NULL,
    telefono_cliente VARCHAR(20) NULL,
    direccion_entrega TEXT NOT NULL,
    ciudad_entrega VARCHAR(100) NOT NULL,
    region_entrega VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(20) NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) DEFAULT 0,
    envio DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    metodo_pago VARCHAR(20) NULL CHECK (metodo_pago IN ('transferencia', 'tarjeta', 'efectivo', 'otro')),
    estado_pago VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_pago IN ('pendiente', 'pagado', 'cancelado', 'reembolsado', 'fallido')),
    estado_pedido VARCHAR(20) DEFAULT 'nuevo' CHECK (estado_pedido IN ('nuevo', 'confirmado', 'en_proceso', 'enviado', 'entregado', 'cancelado', 'completado')),
    numero_seguimiento VARCHAR(100) NULL,
    notas TEXT NULL,
    notas_internas TEXT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
);

CREATE INDEX idx_pedidos_numero ON pedidos(numero_pedido);
CREATE INDEX idx_pedidos_cliente ON pedidos(cliente_id);
CREATE INDEX idx_pedidos_estado_pedido ON pedidos(estado_pedido);
CREATE INDEX idx_pedidos_estado_pago ON pedidos(estado_pago);
CREATE INDEX idx_pedidos_fecha ON pedidos(fecha_pedido);

CREATE TRIGGER update_pedidos_updated_at BEFORE UPDATE ON pedidos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Items de Pedido (depende de pedidos y productos)
DROP TABLE IF EXISTS pedido_items CASCADE;
CREATE TABLE pedido_items (
    id SERIAL PRIMARY KEY,
    pedido_id INTEGER NOT NULL,
    producto_id INTEGER NULL,
    nombre_producto VARCHAR(200) NOT NULL,
    sku VARCHAR(50) NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    atributos TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

CREATE INDEX idx_pedido_items_pedido ON pedido_items(pedido_id);
CREATE INDEX idx_pedido_items_producto ON pedido_items(producto_id);

-- Tabla de Mensajes/Consultas
DROP TABLE IF EXISTS mensajes CASCADE;
CREATE TABLE mensajes (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NULL,
    asunto VARCHAR(200) NULL,
    mensaje TEXT NOT NULL,
    estado VARCHAR(20) DEFAULT 'nuevo' CHECK (estado IN ('nuevo', 'leido', 'pendiente', 'respondido', 'archivado')),
    leido BOOLEAN DEFAULT FALSE,
    respuesta TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TIMESTAMP NULL
);

CREATE INDEX idx_mensajes_estado ON mensajes(estado);
CREATE INDEX idx_mensajes_leido ON mensajes(leido);
CREATE INDEX idx_mensajes_fecha ON mensajes(fecha_creacion);

-- Tabla de Cotizaciones (depende de clientes)
DROP TABLE IF EXISTS cotizaciones CASCADE;
CREATE TABLE cotizaciones (
    id SERIAL PRIMARY KEY,
    numero_cotizacion VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INTEGER NULL,
    nombre_cliente VARCHAR(200) NOT NULL,
    email_cliente VARCHAR(100) NOT NULL,
    telefono_cliente VARCHAR(20) NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aprobada', 'rechazada', 'vencida', 'convertida')),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_vencimiento DATE NULL,
    notas TEXT NULL,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
);

CREATE INDEX idx_cotizaciones_numero ON cotizaciones(numero_cotizacion);
CREATE INDEX idx_cotizaciones_cliente ON cotizaciones(cliente_id);
CREATE INDEX idx_cotizaciones_estado ON cotizaciones(estado);
CREATE INDEX idx_cotizaciones_vencimiento ON cotizaciones(fecha_vencimiento);

CREATE TRIGGER update_cotizaciones_updated_at BEFORE UPDATE ON cotizaciones
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Items de Cotización (depende de cotizaciones y productos)
DROP TABLE IF EXISTS cotizacion_items CASCADE;
CREATE TABLE cotizacion_items (
    id SERIAL PRIMARY KEY,
    cotizacion_id INTEGER NOT NULL,
    producto_id INTEGER NULL,
    nombre_producto VARCHAR(200) NOT NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    atributos TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

CREATE INDEX idx_cotizacion_items_cotizacion ON cotizacion_items(cotizacion_id);
CREATE INDEX idx_cotizacion_items_producto ON cotizacion_items(producto_id);

-- Tabla de Imágenes Web
DROP TABLE IF EXISTS imagenes_web CASCADE;
CREATE TABLE imagenes_web (
    id SERIAL PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('logo', 'banner', 'slider', 'icono', 'fondo', 'galeria')),
    ubicacion VARCHAR(100) NULL,
    titulo VARCHAR(200) NULL,
    descripcion TEXT NULL,
    alt_text VARCHAR(255) NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    orden INTEGER DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_imagenes_web_tipo ON imagenes_web(tipo);
CREATE INDEX idx_imagenes_web_ubicacion ON imagenes_web(ubicacion);
CREATE INDEX idx_imagenes_web_activo ON imagenes_web(activo);

CREATE TRIGGER update_imagenes_web_updated_at BEFORE UPDATE ON imagenes_web
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Movimientos de Inventario (depende de productos y usuarios)
DROP TABLE IF EXISTS movimientos_inventario CASCADE;
CREATE TABLE movimientos_inventario (
    id SERIAL PRIMARY KEY,
    producto_id INTEGER NOT NULL,
    tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('entrada', 'salida', 'ajuste', 'transferencia', 'devolucion')),
    cantidad INTEGER NOT NULL,
    stock_anterior INTEGER NOT NULL,
    stock_nuevo INTEGER NOT NULL,
    motivo VARCHAR(255) NULL,
    referencia VARCHAR(100) NULL,
    usuario_id INTEGER NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE INDEX idx_movimientos_producto ON movimientos_inventario(producto_id);
CREATE INDEX idx_movimientos_tipo ON movimientos_inventario(tipo);
CREATE INDEX idx_movimientos_fecha ON movimientos_inventario(fecha_movimiento);

-- Tabla de Configuración
DROP TABLE IF EXISTS configuracion CASCADE;
CREATE TABLE configuracion (
    id SERIAL PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT NULL,
    tipo VARCHAR(20) DEFAULT 'texto' CHECK (tipo IN ('texto', 'numero', 'booleano', 'json')),
    descripcion TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_configuracion_clave ON configuracion(clave);

CREATE TRIGGER update_configuracion_updated_at BEFORE UPDATE ON configuracion
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Tabla de Ventas (depende de pedidos y productos)
DROP TABLE IF EXISTS ventas CASCADE;
CREATE TABLE ventas (
    id SERIAL PRIMARY KEY,
    pedido_id INTEGER NOT NULL,
    producto_id INTEGER NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

CREATE INDEX idx_ventas_pedido ON ventas(pedido_id);
CREATE INDEX idx_ventas_producto ON ventas(producto_id);
CREATE INDEX idx_ventas_fecha ON ventas(fecha_venta);

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Usuario administrador por defecto
-- Email: admin@haiswimwear.com
-- Password: admin123 (CAMBIAR EN PRODUCCIÓN)
-- Nota: Si pgcrypto no funciona, usa password_hash de PHP
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@haiswimwear.com', crypt('admin123', gen_salt('bf')), 'super_admin')
ON CONFLICT (email) DO UPDATE SET 
    nombre = EXCLUDED.nombre,
    password = EXCLUDED.password,
    rol = EXCLUDED.rol;

-- Categorías iniciales
INSERT INTO categorias (nombre, slug, descripcion, orden) VALUES
('Bikini', 'bikini', 'Bikinis de dos piezas', 1),
('Traje de Baño', 'traje-bano', 'Trajes de baño enteros', 2),
('Bikini Entero', 'bikini-entero', 'Bikinis de una pieza', 3),
('Accesorios', 'accesorios', 'Accesorios de playa', 4)
ON CONFLICT (slug) DO UPDATE SET
    nombre = EXCLUDED.nombre,
    descripcion = EXCLUDED.descripcion,
    orden = EXCLUDED.orden;

-- Configuración inicial
INSERT INTO configuracion (clave, valor, tipo, descripcion) VALUES
('nombre_sitio', 'Hai Swimwear', 'texto', 'Nombre del sitio web'),
('email_contacto', 'contacto@haiswimwear.com', 'texto', 'Email de contacto'),
('telefono_contacto', '+56 9 1234 5678', 'texto', 'Teléfono de contacto'),
('color_primario', '#000000', 'texto', 'Color primario del sitio'),
('color_secundario', '#ffffff', 'texto', 'Color secundario del sitio'),
('envio_rm', '3490', 'numero', 'Costo de envío a Región Metropolitana'),
('envio_regiones', '6390', 'numero', 'Costo de envío a Regiones'),
('stock_minimo_alerta', '10', 'numero', 'Stock mínimo para alertas')
ON CONFLICT (clave) DO UPDATE SET
    valor = EXCLUDED.valor,
    tipo = EXCLUDED.tipo,
    descripcion = EXCLUDED.descripcion;

-- ============================================
-- PERMISOS
-- ============================================

-- Dar permisos al usuario postgres
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO postgres;

-- Dar permisos en secuencias (para SERIAL)
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO postgres;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
