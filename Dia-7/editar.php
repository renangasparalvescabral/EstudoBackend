<?php
/**
 * Dia 7 - Editar Usuário
 *
 * Conceitos:
 * - SELECT com WHERE para buscar um registro
 * - UPDATE com Prepared Statements
 */

require_once 'config.php';

$erros = [];
$sucesso = false;
$usuario = null;

// Obtém o ID da URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: listar.php');
    exit;
}

try {
    $pdo = conectar();

    // Busca o usuário pelo ID
    $stmt = $pdo->prepare('SELECT id, nome, email, idade, mensagem FROM usuarios WHERE id = ?');
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        header('Location: listar.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro: ' . $e->getMessage());
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Validações
    if (empty($nome) || strlen($nome) < 3) {
        $erros['nome'] = 'Nome deve ter pelo menos 3 caracteres';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Email inválido';
    }

    if (empty($idade) || !is_numeric($idade) || $idade < 18 || $idade > 120) {
        $erros['idade'] = 'Idade deve ser entre 18 e 120';
    }

    if (empty($erros)) {
        try {
            // Verifica se email já existe (exceto o próprio usuário)
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
            $stmt->execute([$email, $id]);

            if ($stmt->fetch()) {
                $erros['email'] = 'Este email já está em uso';
            } else {
                // UPDATE
                $sql = 'UPDATE usuarios
                        SET nome = :nome, email = :email, idade = :idade, mensagem = :mensagem
                        WHERE id = :id';

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':idade' => (int) $idade,
                    ':mensagem' => $mensagem ?: null,
                    ':id' => $id
                ]);

                $sucesso = true;

                // Atualiza os dados exibidos
                $usuario['nome'] = $nome;
                $usuario['email'] = $email;
                $usuario['idade'] = $idade;
                $usuario['mensagem'] = $mensagem;
            }

        } catch (PDOException $e) {
            $erros['banco'] = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dia 7 - Editar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <a href="index.php">Cadastrar</a>
        <a href="listar.php">Ver Usuários</a>
    </nav>

    <div class="container">
        <h1>Editar Usuário #<?= $id ?></h1>

        <?php if ($sucesso): ?>
            <div class="alerta sucesso">
                <p>Usuário atualizado com sucesso!</p>
                <a href="listar.php" class="link">Voltar para lista</a>
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
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" value="<?= e($usuario['nome']) ?>">
            </div>

            <div class="campo">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= e($usuario['email']) ?>">
            </div>

            <div class="campo">
                <label for="idade">Idade *</label>
                <input type="number" id="idade" name="idade" value="<?= e($usuario['idade']) ?>" min="18" max="120">
            </div>

            <div class="campo">
                <label for="mensagem">Mensagem</label>
                <textarea id="mensagem" name="mensagem" rows="3"><?= e($usuario['mensagem'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-enviar">Salvar Alterações</button>
                <a href="listar.php" class="btn btn-cancelar">Cancelar</a>
            </div>
        </form>

        <section class="conceitos">
            <h2>Conceitos Utilizados</h2>
            <ul>
                <li><code>UPDATE</code> - Atualiza registro existente</li>
                <li><code>WHERE id = ?</code> - Filtra por ID específico</li>
                <li><code>filter_input()</code> - Valida entrada da URL</li>
            </ul>
        </section>
    </div>

</body>
</html>
