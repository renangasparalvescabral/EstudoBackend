<?php
/**
 * Dia 7 - Excluir Usuário
 *
 * Conceitos:
 * - DELETE com Prepared Statements
 * - Redirecionamento após ação
 */

require_once 'config.php';

// Obtém e valida o ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: listar.php');
    exit;
}

try {
    $pdo = conectar();

    // Verifica se o usuário existe
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE id = ?');
    $stmt->execute([$id]);

    if (!$stmt->fetch()) {
        header('Location: listar.php');
        exit;
    }

    // DELETE
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
    $stmt->execute([$id]);

    // Redireciona com mensagem de sucesso
    header('Location: listar.php?excluido=1');
    exit;

} catch (PDOException $e) {
    die('Erro ao excluir: ' . $e->getMessage());
}
