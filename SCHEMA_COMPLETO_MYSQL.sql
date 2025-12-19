-- ============================================
-- BASE DE DATOS COMPLETA PARA HAI SWIMWEAR
-- MySQL / MariaDB
-- ============================================
-- Ejecuta este script completo en phpMyAdmin o MySQL
-- IMPORTANTE: Ejecuta todo el script de una vez
-- ============================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS hai_swimwear CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hai_swimwear;

-- ============================================
-- TABLAS (ORDEN CORRECTO - SIN FOREIGN KEYS PRIMERO)
-- ============================================

-- Tabla de Usuarios/Administradores
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'super_admin', 'editor') DEFAULT 'admin',
    activo TINYINT(1) DEFAULT 1,
    ultimo_acceso TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Categorías
DROP TABLE IF EXISTS categorias;
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT NULL,
    imagen VARCHAR(255) NULL,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Productos (depende de categorias)
DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    sku VARCHAR(50) UNIQUE NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    categoria_id INT NULL,
    subcategoria VARCHAR(100) NULL,
    descripcion_corta TEXT NULL,
    descripcion_larga TEXT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    precio_anterior DECIMAL(10, 2) NULL,
    stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    peso DECIMAL(8, 2) NULL,
    dimensiones VARCHAR(100) NULL,
    incluye_iva TINYINT(1) DEFAULT 1,
    producto_destacado TINYINT(1) DEFAULT 0,
    estado ENUM('activo', 'inactivo', 'agotado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    INDEX idx_sku (sku),
    INDEX idx_slug (slug),
    INDEX idx_categoria (categoria_id),
    INDEX idx_estado (estado),
    INDEX idx_destacado (producto_destacado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Imágenes de Productos (depende de productos)
DROP TABLE IF EXISTS producto_imagenes;
CREATE TABLE producto_imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0,
    es_principal TINYINT(1) DEFAULT 0,
    alt_text VARCHAR(255) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    INDEX idx_producto (producto_id),
    INDEX idx_principal (es_principal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Atributos de Productos (depende de productos)
DROP TABLE IF EXISTS producto_atributos;
CREATE TABLE producto_atributos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo ENUM('talla', 'color', 'soporte', 'material') NOT NULL,
    valor VARCHAR(100) NOT NULL,
    stock INT DEFAULT 0,
    precio_adicional DECIMAL(10, 2) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    INDEX idx_producto (producto_id),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Clientes
DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    total_pedidos INT DEFAULT 0,
    estado ENUM('activo', 'inactivo', 'bloqueado') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Pedidos (depende de clientes)
DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INT NULL,
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
    metodo_pago ENUM('transferencia', 'tarjeta', 'efectivo', 'otro') NULL,
    estado_pago ENUM('pendiente', 'pagado', 'cancelado', 'reembolsado', 'fallido') DEFAULT 'pendiente',
    estado_pedido ENUM('nuevo', 'confirmado', 'en_proceso', 'enviado', 'entregado', 'cancelado', 'completado') DEFAULT 'nuevo',
    numero_seguimiento VARCHAR(100) NULL,
    notas TEXT NULL,
    notas_internas TEXT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    INDEX idx_numero (numero_pedido),
    INDEX idx_cliente (cliente_id),
    INDEX idx_estado_pedido (estado_pedido),
    INDEX idx_estado_pago (estado_pago),
    INDEX idx_fecha (fecha_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Items de Pedido (depende de pedidos y productos)
DROP TABLE IF EXISTS pedido_items;
CREATE TABLE pedido_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NULL,
    nombre_producto VARCHAR(200) NOT NULL,
    sku VARCHAR(50) NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    atributos TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,
    INDEX idx_pedido (pedido_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Mensajes/Consultas
DROP TABLE IF EXISTS mensajes;
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NULL,
    asunto VARCHAR(200) NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('nuevo', 'leido', 'pendiente', 'respondido', 'archivado') DEFAULT 'nuevo',
    leido TINYINT(1) DEFAULT 0,
    respuesta TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TIMESTAMP NULL,
    INDEX idx_estado (estado),
    INDEX idx_leido (leido),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Cotizaciones (depende de clientes)
DROP TABLE IF EXISTS cotizaciones;
CREATE TABLE cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_cotizacion VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INT NULL,
    nombre_cliente VARCHAR(200) NOT NULL,
    email_cliente VARCHAR(100) NOT NULL,
    telefono_cliente VARCHAR(20) NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    estado ENUM('pendiente', 'aprobada', 'rechazada', 'vencida', 'convertida') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_vencimiento DATE NULL,
    notas TEXT NULL,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    INDEX idx_numero (numero_cotizacion),
    INDEX idx_cliente (cliente_id),
    INDEX idx_estado (estado),
    INDEX idx_vencimiento (fecha_vencimiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Items de Cotización (depende de cotizaciones y productos)
DROP TABLE IF EXISTS cotizacion_items;
CREATE TABLE cotizacion_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cotizacion_id INT NOT NULL,
    producto_id INT NULL,
    nombre_producto VARCHAR(200) NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    atributos TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,
    INDEX idx_cotizacion (cotizacion_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Imágenes Web
DROP TABLE IF EXISTS imagenes_web;
CREATE TABLE imagenes_web (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    tipo ENUM('logo', 'banner', 'slider', 'icono', 'fondo', 'galeria') NOT NULL,
    ubicacion VARCHAR(100) NULL,
    titulo VARCHAR(200) NULL,
    descripcion TEXT NULL,
    alt_text VARCHAR(255) NULL,
    es_principal TINYINT(1) DEFAULT 0,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_ubicacion (ubicacion),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Movimientos de Inventario (depende de productos y usuarios)
DROP TABLE IF EXISTS movimientos_inventario;
CREATE TABLE movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo ENUM('entrada', 'salida', 'ajuste', 'transferencia', 'devolucion') NOT NULL,
    cantidad INT NOT NULL,
    stock_anterior INT NOT NULL,
    stock_nuevo INT NOT NULL,
    motivo VARCHAR(255) NULL,
    referencia VARCHAR(100) NULL,
    usuario_id INT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_producto (producto_id),
    INDEX idx_tipo (tipo),
    INDEX idx_fecha (fecha_movimiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Configuración
DROP TABLE IF EXISTS configuracion;
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT NULL,
    tipo ENUM('texto', 'numero', 'booleano', 'json') DEFAULT 'texto',
    descripcion TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Ventas (depende de pedidos y productos)
DROP TABLE IF EXISTS ventas;
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,
    INDEX idx_pedido (pedido_id),
    INDEX idx_producto (producto_id),
    INDEX idx_fecha (fecha_venta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Usuario administrador por defecto
-- Email: admin@haiswimwear.com
-- Password: admin123 (CAMBIAR EN PRODUCCIÓN)
-- Nota: La contraseña se hashea con password_hash() de PHP
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@haiswimwear.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin')
ON DUPLICATE KEY UPDATE 
    nombre = VALUES(nombre),
    password = VALUES(password),
    rol = VALUES(rol);

-- Categorías iniciales
INSERT INTO categorias (nombre, slug, descripcion, orden) VALUES
('Bikini', 'bikini', 'Bikinis de dos piezas', 1),
('Traje de Baño', 'traje-bano', 'Trajes de baño enteros', 2),
('Bikini Entero', 'bikini-entero', 'Bikinis de una pieza', 3),
('Accesorios', 'accesorios', 'Accesorios de playa', 4)
ON DUPLICATE KEY UPDATE
    nombre = VALUES(nombre),
    descripcion = VALUES(descripcion),
    orden = VALUES(orden);

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
ON DUPLICATE KEY UPDATE
    valor = VALUES(valor),
    tipo = VALUES(tipo),
    descripcion = VALUES(descripcion);

-- ============================================
-- FIN DEL SCRIPT
-- ============================================



