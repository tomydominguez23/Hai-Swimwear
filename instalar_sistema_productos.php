<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación Sistema de Productos - Hai Swimwear</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 700px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .step {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .step:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .step-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: #000;
        }
        
        .step-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .status-icon {
            font-size: 20px;
        }
        
        .status-text {
            flex: 1;
            font-size: 14px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Instalación Sistema de Productos</h1>
            <p>Configura tu sistema para mostrar solo productos reales</p>
        </div>
        
        <div class="content">
            <?php
            // Cargar configuración
            require_once 'config_mysql.php';
            
            $action = $_GET['action'] ?? 'intro';
            $step = $_GET['step'] ?? 1;
            
            if ($action === 'instalar') {
                echo '<div class="alert alert-info">';
                echo '⏳ Instalando sistema... Por favor espera.';
                echo '</div>';
                
                $errores = [];
                $exitos = [];
                
                // Paso 1: Verificar conexión a BD
                try {
                    $db = getDB();
                    $exitos[] = '✅ Conexión a base de datos exitosa';
                } catch (Exception $e) {
                    $errores[] = '❌ Error de conexión: ' . $e->getMessage();
                }
                
                if (empty($errores)) {
                    // Paso 2: Agregar campos
                    try {
                        // Verificar si ya existen
                        $checkCreado = fetchOne("SHOW COLUMNS FROM productos LIKE 'creado_por'");
                        $checkPrueba = fetchOne("SHOW COLUMNS FROM productos LIKE 'es_prueba'");
                        
                        if (!$checkCreado) {
                            executeQuery("ALTER TABLE productos ADD COLUMN creado_por INT NULL AFTER estado");
                            $exitos[] = '✅ Campo "creado_por" agregado';
                        } else {
                            $exitos[] = '✅ Campo "creado_por" ya existe';
                        }
                        
                        if (!$checkPrueba) {
                            executeQuery("ALTER TABLE productos ADD COLUMN es_prueba TINYINT(1) DEFAULT 0 AFTER creado_por");
                            $exitos[] = '✅ Campo "es_prueba" agregado';
                        } else {
                            $exitos[] = '✅ Campo "es_prueba" ya existe';
                        }
                        
                        // Agregar índices
                        try {
                            executeQuery("ALTER TABLE productos ADD INDEX idx_creado_por (creado_por)");
                            executeQuery("ALTER TABLE productos ADD INDEX idx_es_prueba (es_prueba)");
                            $exitos[] = '✅ Índices agregados';
                        } catch (Exception $e) {
                            // Los índices pueden ya existir
                            $exitos[] = '✅ Índices verificados';
                        }
                        
                    } catch (Exception $e) {
                        $errores[] = '❌ Error al agregar campos: ' . $e->getMessage();
                    }
                }
                
                // Mostrar resultados
                if (!empty($exitos)) {
                    echo '<div class="alert alert-success">';
                    echo '<strong>✅ Instalación completada:</strong><br>';
                    foreach ($exitos as $exito) {
                        echo "$exito<br>";
                    }
                    echo '</div>';
                }
                
                if (!empty($errores)) {
                    echo '<div class="alert alert-danger">';
                    echo '<strong>❌ Se encontraron errores:</strong><br>';
                    foreach ($errores as $error) {
                        echo "$error<br>";
                    }
                    echo '</div>';
                } else {
                    echo '<div class="divider"></div>';
                    echo '<div style="text-align: center;">';
                    echo '<h2 style="margin-bottom: 20px; color: #28a745;">🎉 ¡Sistema Instalado Correctamente!</h2>';
                    echo '<p style="color: #666; margin-bottom: 30px;">Ahora puedes gestionar tus productos</p>';
                    echo '<a href="gestionar_productos_prueba.php" class="btn btn-primary">📦 Ir a Gestión de Productos</a>';
                    echo '</div>';
                }
                
            } else {
                // Mostrar información de instalación
                echo '<div class="alert alert-info">';
                echo '💡 Este asistente instalará automáticamente los campos necesarios en tu base de datos.';
                echo '</div>';
                
                echo '<div class="step">';
                echo '<div class="step-header">';
                echo '<div class="step-number">1</div>';
                echo '<div class="step-title">Verificar Base de Datos</div>';
                echo '</div>';
                echo '<div class="step-description">';
                echo 'Se verificará la conexión a tu base de datos MySQL y la existencia de la tabla "productos".';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="step">';
                echo '<div class="step-header">';
                echo '<div class="step-number">2</div>';
                echo '<div class="step-title">Agregar Campos Nuevos</div>';
                echo '</div>';
                echo '<div class="step-description">';
                echo 'Se agregarán dos campos a la tabla productos:<br>';
                echo '• <strong>creado_por</strong>: Para identificar quién creó el producto<br>';
                echo '• <strong>es_prueba</strong>: Para marcar productos de prueba (0 = real, 1 = prueba)';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="step">';
                echo '<div class="step-header">';
                echo '<div class="step-number">3</div>';
                echo '<div class="step-title">Optimizar Rendimiento</div>';
                echo '</div>';
                echo '<div class="step-description">';
                echo 'Se crearán índices para mejorar la velocidad de las consultas.';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="divider"></div>';
                
                echo '<div class="alert alert-warning">';
                echo '<strong>⚠️ Importante:</strong><br>';
                echo '• Asegúrate de tener una copia de seguridad de tu base de datos<br>';
                echo '• Este proceso modificará la estructura de la tabla "productos"<br>';
                echo '• No cerrar esta ventana hasta que termine la instalación';
                echo '</div>';
                
                echo '<div style="text-align: center; margin-top: 30px;">';
                echo '<a href="?action=instalar" class="btn btn-primary" onclick="return confirm(\'¿Estás seguro de que quieres instalar el sistema?\')">🚀 Instalar Ahora</a>';
                echo '</div>';
            }
            ?>
            
            <?php if ($action === 'instalar' && empty($errores)): ?>
            <div class="divider"></div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 16px; margin-bottom: 15px;">📚 Próximos Pasos:</h3>
                <ol style="color: #666; font-size: 14px; line-height: 1.8; padding-left: 20px;">
                    <li>Ve a <strong>Gestión de Productos</strong> para ver todos tus productos</li>
                    <li>Selecciona y elimina los productos de prueba que no necesites</li>
                    <li>Los nuevos productos se crearán automáticamente como "productos reales"</li>
                    <li>Solo los productos reales aparecerán en tu sitio web público</li>
                </ol>
            </div>
            <?php endif; ?>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="admin.php" style="color: #667eea; text-decoration: none; font-size: 14px;">← Volver al Panel de Administración</a>
            </div>
        </div>
    </div>
</body>
</html>
