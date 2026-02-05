<?php
/**
 * API de Busca - Dia 4
 * Recebe requisições POST com JSON e retorna resultados
 */

// Headers para permitir requisições AJAX e retornar JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Responde requisições OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
    exit;
}

// Lê o corpo da requisição JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Valida se o JSON é válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}

// Obtém o termo de busca
$termo = trim($data['busca'] ?? '');

// Valida se o termo não está vazio
if (empty($termo)) {
    http_response_code(400);
    echo json_encode(['error' => 'O campo de busca está vazio']);
    exit;
}

// Simula dados de exemplo (depois você pode conectar a um banco de dados)
$produtos = [
    ['id' => 1, 'nome' => 'Notebook Dell', 'preco' => 3500.00],
    ['id' => 2, 'nome' => 'Mouse Logitech', 'preco' => 150.00],
    ['id' => 3, 'nome' => 'Teclado Mecânico', 'preco' => 450.00],
    ['id' => 4, 'nome' => 'Monitor Samsung', 'preco' => 1200.00],
    ['id' => 5, 'nome' => 'Headset Gamer', 'preco' => 300.00],
    ['id' => 6, 'nome' => 'Webcam HD', 'preco' => 250.00],
    ['id' => 7, 'nome' => 'SSD 500GB', 'preco' => 400.00],
    ['id' => 8, 'nome' => 'Memória RAM 16GB', 'preco' => 350.00],
];

// Filtra produtos que contêm o termo de busca (case insensitive)
$resultados = array_filter($produtos, function($produto) use ($termo) {
    return stripos($produto['nome'], $termo) !== false;
});

// Reindexa o array
$resultados = array_values($resultados);

// Monta a resposta
$totalResultados = count($resultados);

if ($totalResultados > 0) {
    echo json_encode([
        'success' => true,
        'message' => "Encontrado(s) {$totalResultados} resultado(s) para \"{$termo}\"",
        'termo' => $termo,
        'total' => $totalResultados,
        'resultados' => $resultados
    ]);
} else {
    echo json_encode([
        'success' => true,
        'message' => "Nenhum resultado encontrado para \"{$termo}\"",
        'termo' => $termo,
        'total' => 0,
        'resultados' => []
    ]);
}
