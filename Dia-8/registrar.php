<?php
/**
 * Dia 8 - Página de Registro
 */

require_once 'auth.php';

// Se já está logado, redireciona
redirecionarSeLogado();

$erros = [];
$sucesso = false;
$dados = [
    'nome' => '',
    'email' => '',
    'idade' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['idade'] = trim($_POST['idade'] ?? '');
    $dados['senha'] = $_POST['senha'] ?? '';
    $dados['confirmar_senha'] = $_POST['confirmar_senha'] ?? '';

    $resultado = registrar($dados);

    if ($resultado['sucesso']) {
        $sucesso = true;
        $dados = ['nome' => '', 'email' => '', 'idade' => ''];
    } else {
        $erros = $resultado['erros'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Dia 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Criar Conta</h1>
        <p class="subtitulo">Preencha os dados abaixo</p>

        <?php if ($sucesso): ?>
            <div class="alerta sucesso">
                <h3>Conta criada com sucesso!</h3>
                <p><a href="login.php">Clique aqui para fazer login</a></p>
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
            <div class="campo <?= isset($erros['nome']) ? 'campo-erro' : '' ?>">
                <label for="nome">Nome *</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= e($dados['nome']) ?>"
                    placeholder="Seu nome"
                    required
                >
            </div>

            <div class="campo <?= isset($erros['email']) ? 'campo-erro' : '' ?>">
                <label for="email">Email *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($dados['email']) ?>"
                    placeholder="seu@email.com"
                    required
                >
            </div>

            <div class="campo">
                <label for="idade">Idade</label>
                <input
                    type="number"
                    id="idade"
                    name="idade"
                    value="<?= e($dados['idade']) ?>"
                    placeholder="Sua idade"
                    min="18"
                    max="120"
                >
            </div>

            <div class="campo <?= isset($erros['senha']) ? 'campo-erro' : '' ?>">
                <label for="senha">Senha *</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Mínimo 6 caracteres"
                    required
                >
            </div>

            <div class="campo <?= isset($erros['confirmar_senha']) ? 'campo-erro' : '' ?>">
                <label for="confirmar_senha">Confirmar Senha *</label>
                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="Repita a senha"
                    required
                >
            </div>

            <button type="submit" class="btn-enviar">Criar Conta</button>
        </form>

        <p class="link-registro">
            Já tem conta? <a href="login.php">Fazer login</a>
        </p>

        <a href="index.php" class="voltar">← Voltar</a>
    </div>

</body>
</html>
