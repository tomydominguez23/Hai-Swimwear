<?php
/**
 * Contenido del Panel de Administración
 * Este archivo contiene todo el HTML del panel
 */

// Incluir el contenido del index.html pero adaptado para PHP
// Por ahora, vamos a incluir las secciones principales
?>

<!-- Page Content -->
<div class="page-content" id="pageContent">
    <!-- Dashboard Page -->
    <div class="page active" id="dashboard">
        <div class="welcome-section">
            <h2>Bienvenido al sistema de gestión de Hai Swimwear</h2>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-productos">0</h3>
                    <p>PRODUCTOS TOTALES</p>
                    <span class="stat-change positive">+12.5%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3 id="pedidos-activos">0</h3>
                    <p>PEDIDOS ACTIVOS</p>
                    <span class="stat-change positive">+8.3%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3 id="clientes-registrados">0</h3>
                    <p>CLIENTES REGISTRADOS</p>
                    <span class="stat-change positive">+15.2%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3 id="ventas-mes">$0</h3>
                    <p>VENTAS DEL MES</p>
                    <span class="stat-change positive">+3.1%</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h3 class="section-title">Acciones Rápidas</h3>
            <div class="quick-actions">
                <button class="action-card" data-action="nuevo-producto">
                    <i class="fas fa-plus-circle"></i>
                    <h4>Nuevo Producto</h4>
                    <p>Agregar un nuevo producto al catálogo</p>
                </button>
                <button class="action-card" data-action="gestionar-pedidos">
                    <i class="fas fa-shopping-bag"></i>
                    <h4>Gestionar Pedidos</h4>
                    <p>Ver, editar o procesar pedidos pendientes</p>
                </button>
                <button class="action-card" data-action="control-inventario">
                    <i class="fas fa-warehouse"></i>
                    <h4>Control de Inventario</h4>
                    <p>Administrar stock y existencias</p>
                </button>
                <button class="action-card" data-action="ver-reportes">
                    <i class="fas fa-chart-pie"></i>
                    <h4>Ver Reportes</h4>
                    <p>Análisis y estadísticas detalladas</p>
                </button>
                <button class="action-card" data-action="gestionar-clientes">
                    <i class="fas fa-user-friends"></i>
                    <h4>Gestionar Clientes</h4>
                    <p>Administrar base de datos de clientes</p>
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="section">
            <h3 class="section-title">Actividad Reciente</h3>
            <div class="activity-list" id="activityList">
                <!-- Activity items will be loaded here -->
            </div>
        </div>

        <!-- Charts Section -->
        <div class="section">
            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Ventas por Día</h3>
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Productos Más Vendidos</h3>
                    <div class="top-products" id="topProducts">
                        <!-- Top products will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Page -->
    <div class="page" id="productos">
        <div class="page-header">
            <h2>Gestión de Productos</h2>
            <div class="page-actions">
                <button class="btn btn-secondary" id="exportProductos">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <button class="btn btn-primary" id="nuevoProductoBtn">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </button>
            </div>
        </div>

        <div class="stats-mini">
            <div class="stat-mini">
                <span class="stat-number" id="total-productos-mini">0</span>
                <span class="stat-label">TOTAL</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="productos-activos-mini">0</span>
                <span class="stat-label">ACTIVOS</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="productos-agotados-mini">0</span>
                <span class="stat-label">AGOTADOS</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="productos-bajo-stock-mini">0</span>
                <span class="stat-label">BAJO STOCK</span>
            </div>
        </div>

        <div class="filters-bar">
            <select class="filter-select" id="filterCategoria">
                <option value="">Todas las Categorías</option>
            </select>
            <select class="filter-select" id="filterEstado">
                <option value="">Todos los Estados</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="agotado">Agotado</option>
            </select>
            <input type="text" class="search-input" placeholder="Buscar productos..." id="searchProductos">
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosTableBody">
                    <!-- Products will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pedidos Page -->
    <div class="page" id="pedidos">
        <div class="page-header">
            <h2>Gestión de Pedidos</h2>
        </div>

        <div class="stats-mini">
            <div class="stat-mini">
                <span class="stat-number" id="total-pedidos">0</span>
                <span class="stat-label">TOTAL PEDIDOS</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="pedidos-nuevos">0</span>
                <span class="stat-label">NUEVOS</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="pedidos-proceso">0</span>
                <span class="stat-label">EN PROCESO</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="pedidos-pendientes">0</span>
                <span class="stat-label">PENDIENTES</span>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Orden #</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pedidosTableBody">
                    <!-- Orders will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mensajes Page -->
    <div class="page" id="mensajes">
        <div class="page-header">
            <h2>Centro de Mensajes</h2>
            <button class="btn btn-secondary" id="actualizarMensajes">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>

        <div class="stats-mini">
            <div class="stat-mini">
                <span class="stat-number" id="total-mensajes">0</span>
                <span class="stat-label">Total Mensajes</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="mensajes-nuevos">0</span>
                <span class="stat-label">Nuevos</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="mensajes-pendientes">0</span>
                <span class="stat-label">Pendientes</span>
            </div>
            <div class="stat-mini">
                <span class="stat-number" id="mensajes-respondidos">0</span>
                <span class="stat-label">Respondidos</span>
            </div>
        </div>

        <div class="messages-container" id="messagesContainer">
            <!-- Messages will be loaded here -->
        </div>
    </div>

    <!-- Clientes Page -->
    <div class="page" id="clientes">
        <div class="page-header">
            <h2>Gestión de Clientes</h2>
            <button class="btn btn-primary" id="nuevoCliente">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </button>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Pedidos</th>
                        <th>Total Gastado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <!-- Clients will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cotizaciones Page -->
    <div class="page" id="cotizaciones">
        <div class="page-header">
            <h2>Gestión de Cotizaciones</h2>
            <div class="page-actions">
                <button class="btn btn-secondary">Exportar</button>
                <button class="btn btn-primary">Nueva Cotización</button>
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cotizacionesTableBody">
                    <!-- Quotations will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Imágenes Page -->
    <div class="page" id="imagenes">
        <div class="page-header">
            <h2>Gestión de Imágenes Web</h2>
            <div class="page-actions">
                <button class="btn btn-secondary" id="gestionarUbicaciones">
                    <i class="fas fa-map-marker-alt"></i> Gestionar Ubicaciones
                </button>
                <button class="btn btn-primary" id="subirImagenesBtn">
                    <i class="fas fa-upload"></i> Subir Imágenes
                </button>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" data-tab="galeria">Galería de Imágenes</button>
            <button class="tab-btn" data-tab="ubicaciones">Ubicaciones Específicas</button>
            <button class="tab-btn" data-tab="iconos">Iconos de Servicios</button>
            <button class="tab-btn" data-tab="logo">Logo Principal</button>
        </div>

        <div class="tab-content active" id="galeria">
            <div class="filters-bar">
                <select class="filter-select" id="filterTipoImagen">
                    <option value="">Todos los Tipos</option>
                    <option value="logos">Logos</option>
                    <option value="banners">Banners</option>
                    <option value="sliders">Sliders</option>
                    <option value="iconos">Iconos</option>
                    <option value="fondos">Fondos</option>
                </select>
            </div>
            <div class="images-grid" id="imagesGrid">
                <!-- Images will be loaded here -->
            </div>
        </div>

        <div class="tab-content" id="ubicaciones">
            <button class="btn btn-primary" id="nuevaUbicacion">
                <i class="fas fa-plus"></i> Nueva Ubicación
            </button>
            <div class="locations-list" id="locationsList">
                <!-- Locations will be loaded here -->
            </div>
        </div>

        <div class="tab-content" id="iconos">
            <div class="icons-management" id="iconsManagement">
                <!-- Icons management will be loaded here -->
            </div>
        </div>

        <div class="tab-content" id="logo">
            <div class="logo-management">
                <h3>Gestión de Logo Principal</h3>
                <div class="current-logo" id="currentLogo">
                    <p>Cargando...</p>
                </div>
                <div class="logo-actions">
                    <button class="btn btn-primary" id="subirLogo">
                        <i class="fas fa-upload"></i> Subir Nuevo Logo
                    </button>
                    <button class="btn btn-secondary" id="seleccionarLogoGaleria">
                        <i class="fas fa-images"></i> Seleccionar de Galería
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Categorías Page -->
    <div class="page" id="categorias">
        <div class="page-header">
            <h2>Gestión de Categorías</h2>
            <button class="btn btn-primary" id="nuevaCategoria">
                <i class="fas fa-plus"></i> Nueva Categoría
            </button>
        </div>
        <div class="categories-grid" id="categoriesGrid">
            <!-- Categories will be loaded here -->
        </div>
    </div>

    <!-- Inventario Page -->
    <div class="page" id="inventario">
        <div class="page-header">
            <h2>Control de Inventario Avanzado</h2>
            <div class="page-actions">
                <button class="btn btn-secondary">Exportar Reporte</button>
                <button class="btn btn-secondary">Historial Movimientos</button>
                <button class="btn btn-primary">Ajuste de Stock</button>
            </div>
        </div>
        <div class="inventory-stats" id="inventoryStats">
            <!-- Inventory stats will be loaded here -->
        </div>
    </div>

    <!-- Ventas Page -->
    <div class="page" id="ventas">
        <div class="page-header">
            <h2>Ventas</h2>
        </div>
        <div class="sales-container" id="salesContainer">
            <!-- Sales content will be loaded here -->
        </div>
    </div>

    <!-- Reportes Page -->
    <div class="page" id="reportes">
        <div class="page-header">
            <h2>Análisis y Reportes</h2>
        </div>
        <div class="reports-container" id="reportsContainer">
            <!-- Reports content will be loaded here -->
        </div>
    </div>

    <!-- Configuración Page -->
    <div class="page" id="configuracion">
        <div class="page-header">
            <h2>Configuración del Sistema</h2>
        </div>

        <div class="tabs">
            <button class="tab-btn active" data-tab="general">General</button>
            <button class="tab-btn" data-tab="apariencia">Apariencia</button>
            <button class="tab-btn" data-tab="notificaciones">Notificaciones</button>
            <button class="tab-btn" data-tab="seguridad">Seguridad</button>
            <button class="tab-btn" data-tab="integraciones">Integraciones</button>
        </div>

        <div class="tab-content active" id="general">
            <div class="config-section">
                <h3>Información de la Empresa</h3>
                <form class="config-form">
                    <div class="form-group">
                        <label>Nombre del Sitio</label>
                        <input type="text" class="form-control" value="Hai Swimwear">
                    </div>
                    <div class="form-group">
                        <label>Email de Contacto</label>
                        <input type="email" class="form-control" value="contacto@haiswimwear.com">
                    </div>
                    <div class="form-group">
                        <label>Teléfono de Contacto</label>
                        <input type="tel" class="form-control" value="+56 9 1234 5678">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="apariencia">
            <div class="config-section">
                <h3>Configuración de Apariencia</h3>
                <form class="config-form">
                    <div class="form-group">
                        <label>Color Primario</label>
                        <input type="color" class="form-control" value="#000000">
                    </div>
                    <div class="form-group">
                        <label>Color Secundario</label>
                        <input type="color" class="form-control" value="#ffffff">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="notificaciones">
            <div class="config-section">
                <h3>Configuración de Notificaciones</h3>
                <form class="config-form">
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" checked> Notificaciones por Email
                        </label>
                    </div>
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" checked> Alertas de Stock Bajo
                        </label>
                    </div>
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" checked> Notificaciones de Nuevos Pedidos
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="seguridad">
            <div class="config-section">
                <h3>Configuración de Seguridad</h3>
                <form class="config-form">
                    <div class="form-group">
                        <label>Cambiar Contraseña</label>
                        <input type="password" class="form-control" placeholder="Nueva contraseña">
                    </div>
                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" class="form-control" placeholder="Confirmar contraseña">
                    </div>
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox"> Autenticación de Dos Factores
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="integraciones">
            <div class="config-section">
                <h3>Integraciones</h3>
                <form class="config-form">
                    <div class="form-group">
                        <label>API Key de WhatsApp</label>
                        <input type="text" class="form-control" placeholder="Ingresa tu API Key">
                    </div>
                    <div class="form-group">
                        <label>Token de Telegram</label>
                        <input type="text" class="form-control" placeholder="Ingresa tu Token">
                    </div>
                    <div class="form-group">
                        <label>Google Analytics</label>
                        <input type="text" class="form-control" placeholder="ID de seguimiento">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


