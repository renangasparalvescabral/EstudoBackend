<?php
/**
 * Dia 8 - Editar Perfil
 */

require_once 'auth.php';

exigirLogin();

$usuario = getUsuarioLogado();
$erros = [];
$sucesso = false;

// Busca dados completos do usuário
try {
    $pdo = conectar();
    $stmt = $pdo->prepare('SELECT id, nome, email, idade FROM usuarios WHERE id = ?');
    $stmt->execute([$usuario['id']]);
    $dadosCompletos = $stmt->fetch();
} catch (PDOException $e) {
    die('Erro ao buscar dados');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';

    // Validações
    if (empty($nome) || strlen($nome) < 3) {
        $erros['nome'] = 'Nome deve ter pelo menos 3 caracteres';
    }

    // Se quer alterar senha
    if (!empty($novaSenha)) {
        if (empty($senhaAtual)) {
            $erros['senha_atual'] = 'Digite sua senha atual';
        } else {
            // Verifica senha atual
            $stmt = $pdo->prepare('SELECT senha FROM usuarios WHERE id = ?');
            $stmt->execute([$usuario['id']]);
            $row = $stmt->fetch();

            if (!password_verify($senhaAtual, $row['senha'])) {
                $erros['senha_atual'] = 'Senha atual incorreta';
            } elseif (strlen($novaSenha) < 6) {
                $erros['nova_senha'] = 'Nova senha deve ter pelo menos 6 caracteres';
            }
        }
    }

    if (empty($erros)) {
        try {
            if (!empty($novaSenha)) {
                // Atualiza com nova senha
                $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, idade = ?, senha = ? WHERE id = ?');
                $stmt->execute([$nome, $idade ?: null, $senhaHash, $usuario['id']]);
            } else {
                // Atualiza sem senha
                $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, idade = ? WHERE id = ?');
                $stmt->execute([$nome, $idade ?: null, $usuario['id']]);
            }

            // Atualiza sessão
            $_SESSION['usuario_nome'] = $nome;

            $sucesso = true;
            $dadosCompletos['nome'] = $nome;
            $dadosCompletos['idade'] = $idade;

        } catch (PDOException $e) {
            $erros['banco'] = 'Erro ao salvar';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Dia 8</title>
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

    <div class="container">
        <h1>Editar Perfil</h1>

        <?php if ($sucesso): ?>
            <div class="alerta sucesso">
                <p>Perfil atualizado com sucesso!</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= e($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    value="<?= e($dadosCompletos['email']) ?>"
                    disabled
                >
                <small>O email não pode ser alterado</small>
            </div>

            <div class="campo">
                <label for="nome">Nome</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= e($dadosCompletos['nome']) ?>"
                    required
                >
            </div>

            <div class="campo">
                <label for="idade">Idade</label>
                <input
                    type="number"
                    id="idade"
                    name="idade"
                    value="<?= e($dadosCompletos['idade'] ?? '') ?>"
                    min="18"
                    max="120"
                >
            </div>

            <hr>
            <h3>Alterar Senha (opcional)</h3>

            <div class="campo">
                <label for="senha_atual">Senha Atual</label>
                <input
                    type="password"
                    id="senha_atual"
                    name="senha_atual"
                    placeholder="Digite sua senha atual"
                >
            </div>

            <div class="campo">
                <label for="nova_senha">Nova Senha</label>
                <input
                    type="password"
                    id="nova_senha"
                    name="nova_senha"
                    placeholder="Mínimo 6 caracteres"
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-enviar">Salvar Alterações</button>
                <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

</body>
</html>
