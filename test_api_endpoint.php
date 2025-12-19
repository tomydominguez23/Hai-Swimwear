<?php
/**
 * Prueba del endpoint de la API
 * Abre este archivo para verificar que la API funciona
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Prueba de Endpoint API</h1>";
echo "<pre>";

// Probar endpoint test
echo "1. Probando endpoint: api.php?action=test\n";
echo "   URL: " . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/api.php?action=test\n\n";

$url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/api.php?action=test";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Código HTTP: $httpCode\n";
echo "   Respuesta:\n";
echo "   " . str_repeat("-", 60) . "\n";
echo "   " . htmlspecialchars(substr($response, 0, 500)) . "\n";
echo "   " . str_repeat("-", 60) . "\n\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['success'])) {
        echo "   ✅ API funcionando correctamente\n";
        echo "   Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        echo "   Message: " . ($data['message'] ?? 'N/A') . "\n";
    } else {
        echo "   ⚠️  La API responde pero no devuelve JSON válido\n";
        echo "   Esto puede indicar un error en el código PHP\n";
    }
} else {
    echo "   ❌ Error HTTP: $httpCode\n";
    if ($httpCode === 401) {
        echo "   Necesitas iniciar sesión primero\n";
    }
}

echo "\n2. Para probar otros endpoints, usa:\n";
echo "   - api.php?action=stats (requiere autenticación)\n";
echo "   - api.php?action=productos (requiere autenticación)\n";
echo "   - api.php?action=test (no requiere autenticación)\n";

echo "</pre>";
?>

