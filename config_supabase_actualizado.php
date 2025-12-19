<?php
/**
 * Configuración de conexión a Supabase (PostgreSQL)
 * Hai Swimwear
 * 
 * PROYECTO: rvedynuxwfdbqwgkdgjg
 * URL: https://rvedynuxwfdbqwgkdgjg.supabase.co
 */

// Configuración de Supabase - Base de Datos PostgreSQL
define('SUPABASE_HOST', 'db.rvedynuxwfdbqwgkdgjg.supabase.co');
define('SUPABASE_DB', 'postgres');
define('SUPABASE_USER', 'postgres');
// ⚠️ IMPORTANTE: Necesitas completar esta contraseña
// Obténla en: Supabase Dashboard > Settings > Database > Database password
define('SUPABASE_PASS', ''); // ⬅️ COMPLETA AQUÍ TU CONTRASEÑA
define('SUPABASE_PORT', '5432');

// API Key de Supabase (para uso futuro con API REST)
define('SUPABASE_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJ2ZWR5bnV4d2ZkYnF3Z2tkZ2pnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjU1NTcxODksImV4cCI6MjA4MTEzMzE4OX0.kGw2F2AfuU6WEJIPk28O_B4SKpygcrdzPSa5me1-chQ');
define('SUPABASE_URL', 'https://rvedynuxwfdbqwgkdgjg.supabase.co');

// Configuración de la aplicación
define('APP_NAME', 'Hai Swimwear');
define('APP_URL', 'http://localhost');
define('ADMIN_PATH', '/admin');

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
 * Conexión a Supabase (PostgreSQL)
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            // Supabase requiere SSL para conexiones seguras
            $dsn = "pgsql:host=" . SUPABASE_HOST . 
                   ";port=" . SUPABASE_PORT . 
                   ";dbname=" . SUPABASE_DB . 
                   ";sslmode=require";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false, // No usar conexiones persistentes con Supabase
            ];
            
            $this->conn = new PDO($dsn, SUPABASE_USER, SUPABASE_PASS, $options);
        } catch(PDOException $e) {
            error_log("Error de conexión a Supabase: " . $e->getMessage());
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
        // Si la consulta ya tiene RETURNING, usarla tal cual
        // Si no, agregar RETURNING id para PostgreSQL
        if (stripos($sql, 'RETURNING') === false) {
            $sql .= " RETURNING id";
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? $result['id'] : false;
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
        header('Location: ' . ADMIN_PATH . '/index.php?error=no_permission');
        exit;
    }
}

/**
 * Requerir rol de super administrador
 */
function requireSuperAdmin() {
    requireAuth();
    if (!isSuperAdmin()) {
        header('Location: ' . ADMIN_PATH . '/index.php?error=no_permission');
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
    $config = fetchOne("SELECT valor FROM configuracion WHERE clave = $1", [$key]);
    return $config ? $config['valor'] : $default;
}

/**
 * Actualizar configuración
 */
function setConfig($key, $value) {
    $sql = "INSERT INTO configuracion (clave, valor) VALUES ($1, $2)
            ON CONFLICT (clave) DO UPDATE SET valor = $2";
    return executeQuery($sql, [$key, $value]);
}

/**
 * Verificar contraseña (PostgreSQL con pgcrypto)
 */
function verifyPassword($password, $hash) {
    try {
        $result = fetchOne("SELECT crypt($1, $2) = $2 as match", [$password, $hash]);
        return $result && $result['match'];
    } catch(PDOException $e) {
        // Si pgcrypto no está disponible, usar password_verify de PHP
        return password_verify($password, $hash);
    }
}

/**
 * Hash de contraseña
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Probar conexión a Supabase
 */
function testConnection() {
    try {
        $db = getDB();
        $result = fetchOne("SELECT version() as version");
        return [
            'success' => true,
            'message' => 'Conexión exitosa a Supabase',
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

