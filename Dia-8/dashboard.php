<?php
/**
 * Dia 8 - Dashboard (Área Protegida)
 *
 * Esta página só pode ser acessada por usuários logados
 */

require_once 'auth.php';

// Exige login - redireciona se não estiver autenticado
exigirLogin();

$usuario = getUsuarioLogado();

// Calcula tempo logado
$tempoLogado = time() - ($_SESSION['login_time'] ?? time());
$minutos = floor($tempoLogado / 60);
$segundos = $tempoLogado % 60;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dia 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="dashboard-nav">
        <div class="nav-brand">Sistema</div>
        <div class="nav-user">
            <span>Olá, <?= e($usuario['nome']) ?></span>
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>
    </nav>

    <div class="container dashboard">
        <h1>Dashboard</h1>
        <p class="subtitulo">Área restrita - Somente usuários autenticados</p>

        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon">👤</div>
                <h3>Seus Dados</h3>
                <ul>
                    <li><strong>ID:</strong> <?= $usuario['id'] ?></li>
                    <li><strong>Nome:</strong> <?= e($usuario['nome']) ?></li>
                    <li><strong>Email:</strong> <?= e($usuario['email']) ?></li>
                </ul>
            </div>

            <div class="card">
                <div class="card-icon">🕐</div>
                <h3>Sessão</h3>
                <ul>
                    <li><strong>Logado há:</strong> <?= $minutos ?>m <?= $segundos ?>s</li>
                    <li><strong>Session ID:</strong> <code><?= substr(session_id(), 0, 8) ?>...</code></li>
                </ul>
            </div>

            <div class="card">
                <div class="card-icon">🔒</div>
                <h3>Segurança</h3>
                <p>Esta página está protegida pela função <code>exigirLogin()</code></p>
                <p>Tente acessar diretamente sem estar logado!</p>
            </div>
        </div>

        <section class="session-data">
            <h2>Dados da $_SESSION</h2>
            <pre><?php print_r($_SESSION); ?></pre>
        </section>

        <section class="conceitos">
            <h2>Como Funciona</h2>
            <ol>
                <li><strong>Login:</strong> Verifica email/senha e cria sessão</li>
                <li><strong>$_SESSION:</strong> Armazena dados entre requisições</li>
                <li><strong>exigirLogin():</strong> Protege páginas restritas</li>
                <li><strong>Logout:</strong> Destrói sessão e cookies</li>
            </ol>
        </section>

        <div class="dashboard-actions">
            <a href="perfil.php" class="btn btn-primary">Editar Perfil</a>
            <a href="index.php" class="btn btn-secondary">Página Inicial</a>
        </div>
    </div>

</body>
</html>
