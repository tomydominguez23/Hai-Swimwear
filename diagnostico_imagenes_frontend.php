<?php
// Script para diagnosticar la API de imágenes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Intentar cargar la configuración para conectar a la BD
$configFile = __DIR__ . '/config.php';
if (file_exists(__DIR__ . '/config_mysql.php')) {
    $configFile = __DIR__ . '/config_mysql.php';
} elseif (file_exists(__DIR__ . '/config_supabase.php')) {
    $configFile = __DIR__ . '/config_supabase.php';
}

require_once $configFile;

echo "<h1>Diagnóstico de Imágenes</h1>";

// 1. Verificar conexión BD
echo "<h2>1. Estado de la Base de Datos</h2>";
try {
    if (isset($pdo)) {
        echo "<p style='color:green'>Conexión PDO activa</p>";
    } elseif (isset($conn)) {
        echo "<p style='color:green'>Conexión MySQLi activa</p>";
    } else {
        echo "<p style='color:red'>No hay conexión a BD visible</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

// 2. Consultar Imágenes
echo "<h2>2. Contenido de tabla 'imagenes_web'</h2>";
try {
    $sql = "SELECT * FROM imagenes_web ORDER BY id DESC LIMIT 10";
    if (isset($pdo)) {
        $stmt = $pdo->query($sql);
        $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $conn->query($sql);
        $imagenes = [];
        while ($row = $result->fetch_assoc()) {
            $imagenes[] = $row;
        }
    }

    if (empty($imagenes)) {
        echo "<p>No se encontraron imágenes en la tabla.</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Tipo</th><th>URL</th><th>Ubicación</th></tr>";
        foreach ($imagenes as $img) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($img['id']) . "</td>";
            echo "<td>" . htmlspecialchars($img['tipo']) . "</td>";
            echo "<td>" . htmlspecialchars($img['url']) . "</td>";
            echo "<td>" . htmlspecialchars($img['ubicacion'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error al consultar: " . $e->getMessage() . "</p>";
}

// 3. Probar API URL
echo "<h2>3. Verificación de Archivos</h2>";
$apiPath = __DIR__ . '/api.php';
echo "<p>api.php existe: " . (file_exists($apiPath) ? 'SÍ' : 'NO') . "</p>";

// Simular llamada a API para Logos
echo "<h2>4. Simulación de Respuesta API (Logos)</h2>";
$apiUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api.php?action=imagenes&tipo=logos';
echo "<p>Probando URL: $apiUrl</p>";
?>

<script>
// Prueba de fetch desde JS
console.log('Iniciando prueba de fetch...');
fetch('api.php?action=imagenes&tipo=logos')
    .then(response => response.json())
    .then(data => {
        const div = document.createElement('div');
        div.innerHTML = '<h3>Respuesta JS (Logos):</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
        document.body.appendChild(div);
    })
    .catch(error => {
        const div = document.createElement('div');
        div.innerHTML = '<h3 style="color:red">Error JS:</h3><pre>' + error + '</pre>';
        document.body.appendChild(div);
    });
</script>