<?php
/**
 * API para obtener categorías
 * Hai Swimwear
 */

header('Content-Type: application/json');
require_once '../database/config_supabase.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $activo = $_GET['activo'] ?? true;
        
        $sql = "SELECT c.*, COUNT(p.id) as total_productos 
                FROM categorias c 
                LEFT JOIN productos p ON c.id = p.categoria_id AND p.estado = 'activo'
                WHERE c.activo = $1
                GROUP BY c.id
                ORDER BY c.orden ASC, c.nombre ASC";
        
        $categorias = fetchAll($sql, [$activo]);
        
        jsonResponse(true, 'Categorías obtenidas', $categorias);
    } else {
        http_response_code(405);
        jsonResponse(false, 'Método no permitido', null);
    }
} catch (Exception $e) {
    error_log("Error en categorias.php: " . $e->getMessage());
    http_response_code(500);
    jsonResponse(false, 'Error del servidor', null);
}

?>

