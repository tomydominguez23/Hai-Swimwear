<?php
/**
 * Configuración de conexión a MySQL/MariaDB
 * Hai Swimwear
 */

// Configuración de MySQL/MariaDB
define('DB_HOST', 'localhost');      // Cambiar por el host de tu base de datos (ej. 127.0.0.1 o IP del servidor)
define('DB_NAME', 'hai_swimwear');   // Cambiar por el nombre de tu base de datos
define('DB_USER', 'root');           // Cambiar por tu usuario de MySQL
define('DB_PASS', '');               // Cambiar por tu contraseña de MySQL
define('DB_PORT', '3306');           // Cambiar si usas un puerto diferente

// Configuración de la aplicación
define('APP_NAME', 'Hai Swimwear');
define('APP_URL', 'http://localhost');
define('ADMIN_PATH', '.');

// Configuración de sesión
define('SESSION_LIFETIME', 3600); // 1 hora

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutos

// Configuración de archivos
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Zona horaria
date_default_timezone_set('America/Santiago');

/**
 * Conexión a MySQL/MariaDB
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . 
                   ";port=" . DB_PORT . 
                   ";dbname=" . DB_NAME . 
                   ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            error_log("Error de conexión a MySQL: " . $e->getMessage());
            die("Error de conexión a la base de datos. Por favor, verifica tu configuración.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Obtener conexión a la base de datos
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Función helper para ejecutar consultas preparadas
 */
function executeQuery($sql, $params = []) {
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Error en consulta: " . $e->getMessage());
        error_log("SQL: " . $sql);
        error_log("Params: " . print_r($params, true));
        return false;
    }
}

/**
 * Función helper para obtener un solo registro
 */
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

/**
 * Función helper para obtener múltiples registros
 */
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}

/**
 * Función helper para insertar y obtener el ID
 */
function insertAndGetId($sql, $params = []) {
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $db->lastInsertId();
    } catch(PDOException $e) {
        error_log("Error al insertar: " . $e->getMessage());
        error_log("SQL: " . $sql);
        return false;
    }
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin() {
    return isAuthenticated() && in_array($_SESSION['user_role'], ['admin', 'super_admin']);
}

/**
 * Verificar si el usuario es super administrador
 */
function isSuperAdmin() {
    return isAuthenticated() && $_SESSION['user_role'] === 'super_admin';
}

/**
 * Requerir autenticación
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: ' . ADMIN_PATH . '/login.php');
        exit;
    }
}

/**
 * Requerir rol de administrador
 */
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('Location: ' . ADMIN_PATH . '/admin.php?error=no_permission');
        exit;
    }
}

/**
 * Requerir rol de super administrador
 */
function requireSuperAdmin() {
    requireAuth();
    if (!isSuperAdmin()) {
        header('Location: ' . ADMIN_PATH . '/admin.php?error=no_permission');
        exit;
    }
}

/**
 * Sanitizar entrada
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validar email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Generar respuesta JSON
 */
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Formatear moneda
 */
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.');
}

/**
 * Obtener configuración
 */
function getConfig($key, $default = null) {
    try {
        $config = fetchOne("SELECT valor FROM configuracion WHERE clave = ?", [$key]);
        return $config ? $config['valor'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Actualizar configuración
 */
function setConfig($key, $value) {
    $sql = "INSERT INTO configuracion (clave, valor) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE valor = ?";
    return executeQuery($sql, [$key, $value, $value]);
}

/**
 * Verificar contraseña (MySQL usa password_verify de PHP)
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Hash de contraseña
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Probar conexión a MySQL
 */
function testConnection() {
    try {
        $db = getDB();
        $result = fetchOne("SELECT VERSION() as version");
        return [
            'success' => true,
            'message' => 'Conexión exitosa a MySQL',
            'version' => $result['version'] ?? 'Desconocida'
        ];
    } catch(Exception $e) {
        return [
            'success' => false,
            'message' => 'Error de conexión: ' . $e->getMessage()
        ];
    }
}

?>



