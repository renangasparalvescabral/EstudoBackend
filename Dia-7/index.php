<?php
/**
 * Dia 7 - Cadastro de Usuários com MySQL
 *
 * Conceitos:
 * - INSERT com Prepared Statements
 * - password_hash() para senhas seguras
 * - Verificação de email duplicado
 */

require_once 'config.php';

$erros = [];
$sucesso = false;
$dados = [
    'nome' => '',
    'email' => '',
    'idade' => '',
    'mensagem' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe e sanitiza dados
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['idade'] = trim($_POST['idade'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    $dados['mensagem'] = trim($_POST['mensagem'] ?? '');

    // Validações
    if (empty($dados['nome'])) {
        $erros['nome'] = 'O nome é obrigatório';
    } elseif (strlen($dados['nome']) < 3) {
        $erros['nome'] = 'O nome deve ter pelo menos 3 caracteres';
    }

    if (empty($dados['email'])) {
        $erros['email'] = 'O email é obrigatório';
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Digite um email válido';
    }

    if (empty($dados['idade'])) {
        $erros['idade'] = 'A idade é obrigatória';
    } elseif (!is_numeric($dados['idade']) || $dados['idade'] < 18 || $dados['idade'] > 120) {
        $erros['idade'] = 'A idade deve ser entre 18 e 120 anos';
    }

    if (empty($senha)) {
        $erros['senha'] = 'A senha é obrigatória';
    } elseif (strlen($senha) < 6) {
        $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres';
    }

    if ($senha !== $confirmarSenha) {
        $erros['confirmar_senha'] = 'As senhas não conferem';
    }

    // Se não houver erros, salva no banco
    if (empty($erros)) {
        try {
            $pdo = conectar();

            // Verifica se email já existe
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
            $stmt->execute([$dados['email']]);

            if ($stmt->fetch()) {
                $erros['email'] = 'Este email já está cadastrado';
            } else {
                // Hash da senha (NUNCA salve senha em texto puro!)
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                // INSERT com Prepared Statement
                $sql = 'INSERT INTO usuarios (nome, email, idade, senha, mensagem)
                        VALUES (:nome, :email, :idade, :senha, :mensagem)';

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $dados['nome'],
                    ':email' => $dados['email'],
                    ':idade' => (int) $dados['idade'],
                    ':senha' => $senhaHash,
                    ':mensagem' => $dados['mensagem'] ?: null
                ]);

                $sucesso = true;
                $idInserido = $pdo->lastInsertId();

                // Limpa o formulário
                $dados = ['nome' => '', 'email' => '', 'idade' => '', 'mensagem' => ''];
            }

        } catch (PDOException $e) {
            $erros['banco'] = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dia 7 - MySQL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <a href="index.php" class="active">Cadastrar</a>
        <a href="listar.php">Ver Usuários</a>
    </nav>

    <div class="container">
        <h1>Dia 7 - MySQL com PDO</h1>
        <p class="subtitulo">Cadastro de usuários com banco de dados</p>

        <?php if ($sucesso): ?>
            <div class="alerta sucesso">
                <h3>Usuário cadastrado com sucesso!</h3>
                <p>ID: <?= $idInserido ?></p>
                <a href="listar.php" class="link">Ver todos os usuários</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <h3>Corrija os erros:</h3>
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
                <input type="text" id="nome" name="nome" value="<?= e($dados['nome']) ?>" placeholder="Seu nome">
            </div>

            <div class="campo <?= isset($erros['email']) ? 'campo-erro' : '' ?>">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= e($dados['email']) ?>" placeholder="seu@email.com">
            </div>

            <div class="campo <?= isset($erros['idade']) ? 'campo-erro' : '' ?>">
                <label for="idade">Idade *</label>
                <input type="number" id="idade" name="idade" value="<?= e($dados['idade']) ?>" placeholder="Sua idade" min="18" max="120">
            </div>

            <div class="campo <?= isset($erros['senha']) ? 'campo-erro' : '' ?>">
                <label for="senha">Senha *</label>
                <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="campo <?= isset($erros['confirmar_senha']) ? 'campo-erro' : '' ?>">
                <label for="confirmar_senha">Confirmar Senha *</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha">
            </div>

            <div class="campo">
                <label for="mensagem">Mensagem (opcional)</label>
                <textarea id="mensagem" name="mensagem" placeholder="Uma mensagem..." rows="3"><?= e($dados['mensagem']) ?></textarea>
            </div>

            <button type="submit" class="btn-enviar">Cadastrar</button>
        </form>

        <section class="conceitos">
            <h2>Conceitos MySQL/PDO</h2>
            <ul>
                <li><code>PDO</code> - PHP Data Objects para conexão</li>
                <li><code>prepare()</code> - Prepara query segura</li>
                <li><code>execute()</code> - Executa com parâmetros</li>
                <li><code>password_hash()</code> - Hash seguro de senha</li>
                <li><code>lastInsertId()</code> - Obtém ID inserido</li>
                <li><code>Prepared Statements</code> - Previne SQL Injection</li>
            </ul>
        </section>
    </div>

</body>
</html>
