<?php
/**
 * Dia 8 - Página Inicial
 * Redireciona para dashboard se logado, senão mostra página de boas-vindas
 */

require_once 'auth.php';

$usuario = getUsuarioLogado();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dia 8 - Sistema de Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container welcome">
        <h1>Dia 8 - Login e Sessões</h1>
        <p class="subtitulo">Sistema de autenticação com PHP</p>

        <div class="welcome-box">
            <?php if ($usuario): ?>
                <p class="welcome-msg">Olá, <strong><?= e($usuario['nome']) ?></strong>!</p>
                <div class="welcome-actions">
                    <a href="dashboard.php" class="btn btn-primary">Ir para Dashboard</a>
                    <a href="logout.php" class="btn btn-secondary">Sair</a>
                </div>
            <?php else: ?>
                <p class="welcome-msg">Bem-vindo ao sistema!</p>
                <div class="welcome-actions">
                    <a href="login.php" class="btn btn-primary">Entrar</a>
                    <a href="registrar.php" class="btn btn-secondary">Criar Conta</a>
                </div>
            <?php endif; ?>
        </div>

        <section class="conceitos">
            <h2>Conceitos do Dia 8</h2>
            <ul>
                <li><code>session_start()</code> - Inicia a sessão</li>
                <li><code>$_SESSION</code> - Armazena dados entre páginas</li>
                <li><code>password_verify()</code> - Verifica senha com hash</li>
                <li><code>session_regenerate_id()</code> - Previne session fixation</li>
                <li><code>session_destroy()</code> - Encerra a sessão</li>
            </ul>
        </section>
    </div>

</body>
</html>
