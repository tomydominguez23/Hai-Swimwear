<?php
/**
 * Página de Login - Panel de Administración
 * Hai Swimwear
 */

// Activar visualización de errores para depuración (desactivar en producción estable)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Intentar cargar configuración (MySQL > Supabase > PostgreSQL > Default)
$configLoaded = false;
$configPaths = [
    __DIR__ . '/config_mysql.php',
    __DIR__ . '/../database/config_mysql.php', // Por compatibilidad si se mueve
    __DIR__ . '/config_produccion.php', // Por si acaso
    __DIR__ . '/config.php'
];

foreach ($configPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $configLoaded = true;
        break;
    }
}

if (!$configLoaded) {
    die("Error crítico: No se encontró el archivo de configuración (config_mysql.php). Verifica que el despliegue haya funcionado correctamente.");
}

session_start();

// Si ya está autenticado, redirigir al panel
if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $user = fetchOne("SELECT * FROM usuarios WHERE email = $1 AND activo = true", [$email]);
            
            if ($user) {
                // Verificar contraseña
                if (verifyPassword($password, $user['password'])) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['rol'];
                    $_SESSION['user_name'] = $user['nombre'];
                    
                    // Actualizar último acceso
                    executeQuery("UPDATE usuarios SET ultimo_acceso = CURRENT_TIMESTAMP WHERE id = $1", [$user['id']]);
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Credenciales incorrectas';
                }
            } else {
                $error = 'Credenciales incorrectas';
            }
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            $error = 'Error al iniciar sesión. Por favor, intenta de nuevo.';
        }
    } else {
        $error = 'Por favor, completa todos los campos';
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel de Administración | Hai Swimwear</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #000;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #333;
        }
        .error-message {
            background-color: #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Hai Swimwear</h1>
            <p>Panel de Administración</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>
        
        <div class="login-footer">
            <p>Credenciales por defecto: admin@haiswimwear.com / admin123</p>
        </div>
    </div>
</body>
</html>

