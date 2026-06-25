<?php
// =============================================
// DJAKA COFFEE — Database Connection
// =============================================
// Konfigurasi: sesuaikan dengan database MySQL Anda

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // ganti sesuai user MySQL Anda
define('DB_PASS', '');            // ganti sesuai password MySQL Anda
define('DB_NAME', 'djaka_coffee');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// CORS headers untuk fetch dari HTML
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
