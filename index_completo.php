<?php
/**
 * Panel de Administración - Versión Completa
 * Hai Swimwear
 */

require_once '../database/config_supabase.php';

// Verificar autenticación
requireAuth();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Hai Swimwear</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="logo">Hai Swimwear</h2>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active" data-page="dashboard">
                    <a href="#dashboard" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item" data-page="productos">
                    <a href="#productos" class="nav-link">
                        <i class="fas fa-box"></i>
                        <span>Productos</span>
                        <span class="badge" id="productos-badge">0</span>
                    </a>
                </li>
                <li class="nav-item" data-page="categorias">
                    <a href="#categorias" class="nav-link">
                        <i class="fas fa-tags"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li class="nav-item" data-page="inventario">
                    <a href="#inventario" class="nav-link">
                        <i class="fas fa-warehouse"></i>
                        <span>Inventario</span>
                    </a>
                </li>
                <li class="nav-item" data-page="pedidos">
                    <a href="#pedidos" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pedidos</span>
                        <span class="badge" id="pedidos-badge">0</span>
                    </a>
                </li>
                <li class="nav-item" data-page="clientes">
                    <a href="#clientes" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <li class="nav-item" data-page="mensajes">
                    <a href="#mensajes" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <span>Mensajes</span>
                        <span class="badge" id="mensajes-badge">0</span>
                    </a>
                </li>
                <li class="nav-item" data-page="cotizaciones">
                    <a href="#cotizaciones" class="nav-link">
                        <i class="fas fa-file-invoice"></i>
                        <span>Cotizaciones</span>
                        <span class="badge" id="cotizaciones-badge">0</span>
                    </a>
                </li>
                <li class="nav-item" data-page="imagenes">
                    <a href="#imagenes" class="nav-link">
                        <i class="fas fa-images"></i>
                        <span>Imágenes Web</span>
                    </a>
                </li>
                <li class="nav-item" data-page="ventas">
                    <a href="#ventas" class="nav-link">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Ventas</span>
                    </a>
                </li>
                <li class="nav-item" data-page="reportes">
                    <a href="#reportes" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li class="nav-item" data-page="configuracion">
                    <a href="#configuracion" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?></span>
                    <span class="user-role"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $_SESSION['user_role'] ?? 'Admin'))); ?></span>
                </div>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title" id="pageTitle">Panel de Administración</h1>
            </div>
            <div class="top-bar-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <div class="notifications">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
                <div class="user-menu">
                    <div class="user-avatar-small">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content" id="pageContent">
            <?php include 'content.php'; ?>
            
            <!-- Otras páginas (pedidos, mensajes, etc.) -->
            <?php 
            // Incluir otras páginas desde index.html si es necesario
            // Por ahora están en content.php
            ?>
        </div>
    </main>

    <!-- Modals -->
    <?php include 'modals.php'; ?>

    <script src="script.js"></script>
    <script>
        // Test de API al cargar
        async function testAPI() {
            try {
                const response = await fetch('api.php?action=test');
                const text = await response.text();
                console.log('Respuesta de API:', text.substring(0, 200));
                
                try {
                    const result = JSON.parse(text);
                    if (result.success) {
                        console.log('✅ API funcionando correctamente');
                    }
                } catch (e) {
                    console.error('❌ API no devuelve JSON válido');
                    console.error('Respuesta completa:', text);
                    alert('ERROR: El servidor no está ejecutando PHP correctamente.\n\nAsegúrate de:\n1. Usar index.php (NO index.html)\n2. Tener un servidor PHP funcionando\n3. Ver: admin/DIAGNOSTICO.md');
                }
            } catch (error) {
                console.error('❌ Error al conectar con API:', error);
            }
        }
        
        // Ejecutar test al cargar
        testAPI();
    </script>
</body>
</html>

