<?php
/**
 * Dia 8 - Página de Login
 */

require_once 'auth.php';

// Se já está logado, redireciona
redirecionarSeLogado();

$erro = '';
$email = '';

// Mensagem da sessão (ex: após tentar acessar página protegida)
$mensagem = $_SESSION['mensagem'] ?? null;
$mensagemTipo = $_SESSION['mensagem_tipo'] ?? 'info';
unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']);

// Processa o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $resultado = login($email, $senha);

    if ($resultado['sucesso']) {
        // Redireciona para a página que tentou acessar ou dashboard
        $destino = $_SESSION['redirect_after_login'] ?? 'dashboard.php';
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $destino);
        exit;
    } else {
        $erro = $resultado['erro'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dia 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Login</h1>
        <p class="subtitulo">Entre com sua conta</p>

        <?php if ($mensagem): ?>
            <div class="alerta <?= $mensagemTipo ?>">
                <p><?= e($mensagem) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alerta erro">
                <p><?= e($erro) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($email) ?>"
                    placeholder="seu@email.com"
                    autofocus
                    required
                >
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Sua senha"
                    required
                >
            </div>

            <button type="submit" class="btn-enviar">Entrar</button>
        </form>

        <p class="link-registro">
            Não tem conta? <a href="registrar.php">Criar conta</a>
        </p>

        <a href="index.php" class="voltar">← Voltar</a>
    </div>

</body>
</html>
