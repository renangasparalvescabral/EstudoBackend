<?php
/**
 * Dia 7 - Configuração de Conexão com MySQL
 *
 * Conceitos:
 * - PDO (PHP Data Objects) para conexão segura
 * - Prepared Statements para prevenir SQL Injection
 * - Tratamento de erros de conexão
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'estudo_backend');
define('DB_USER', 'root');        // Usuário padrão do XAMPP
define('DB_PASS', '');            // Senha padrão do XAMPP (vazia)
define('DB_CHARSET', 'utf8mb4');

/**
 * Função para conectar ao banco de dados
 * @return PDO Objeto de conexão PDO
 */
function conectar(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $opcoes = [
            // Lança exceções em caso de erro
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Retorna resultados como array associativo
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Desativa prepared statements emulados (mais seguro)
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
        } catch (PDOException $e) {
            // Em produção, não exiba detalhes do erro
            die('Erro de conexão: ' . $e->getMessage());
        }
    }

    return $pdo;
}

/**
 * Função helper para escapar saída HTML
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
