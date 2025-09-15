<?php
// api/cursos_api.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
    http_response_code(204); 
    exit; 
}

include_once 'models/Curso.php';

$response = ['success' => false, 'data' => null, 'error' => null];

try {
    $curso = new Curso(); // Conexão e consulta do curso

    if (isset($_GET['id']) && (int)$_GET['id'] > 0) { // Consultar por id ou consultar todo mundo - php controla o react native
        // Buscar por ID específico
        $id = (int) $_GET['id'];
        $row = $curso->consultar($id); // Chama o procedure consultar curso
        $response['success'] = true;
        $response['data'] = $row !== false ? $row : null;
    } else {
        // Sem ID → retorna todos os cursos
        $rows = $curso->consultar(null);
        $response['success'] = true;
        $response['data'] = $rows !== false ? $rows : [];
    }
} catch (Throwable $e) {
    http_response_code(500);
    $response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
