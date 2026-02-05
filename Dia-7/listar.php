<?php
/**
 * Dia 7 - Listagem de Usuários
 *
 * Conceitos:
 * - SELECT com PDO
 * - fetchAll() para múltiplos registros
 * - Exibição segura com htmlspecialchars
 */

require_once 'config.php';

$usuarios = [];
$erro = null;
$mensagem = null;

// Mensagem de exclusão
if (isset($_GET['excluido'])) {
    $mensagem = 'Usuário excluído com sucesso!';
}

try {
    $pdo = conectar();

    // SELECT todos os usuários (sem a senha!)
    $sql = 'SELECT id, nome, email, idade, mensagem, criado_em
            FROM usuarios
            ORDER BY criado_em DESC';

    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll();

} catch (PDOException $e) {
    $erro = 'Erro ao buscar usuários: ' . $e->getMessage();
}

// Conta total
$total = count($usuarios);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dia 7 - Listar Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <a href="index.php">Cadastrar</a>
        <a href="listar.php" class="active">Ver Usuários</a>
    </nav>

    <div class="container">
        <h1>Usuários Cadastrados</h1>
        <p class="subtitulo"><?= $total ?> usuário(s) encontrado(s)</p>

        <?php if ($mensagem): ?>
            <div class="alerta sucesso">
                <p><?= e($mensagem) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alerta erro">
                <p><?= e($erro) ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($usuarios)): ?>
            <div class="vazio">
                <p>Nenhum usuário cadastrado ainda.</p>
                <a href="index.php" class="btn-enviar">Cadastrar primeiro usuário</a>
            </div>
        <?php else: ?>
            <div class="lista-usuarios">
                <?php foreach ($usuarios as $usuario): ?>
                    <div class="card-usuario">
                        <div class="card-header">
                            <h3><?= e($usuario['nome']) ?></h3>
                            <span class="id">#<?= $usuario['id'] ?></span>
                        </div>

                        <div class="card-body">
                            <p><strong>Email:</strong> <?= e($usuario['email']) ?></p>
                            <p><strong>Idade:</strong> <?= $usuario['idade'] ?> anos</p>
                            <?php if ($usuario['mensagem']): ?>
                                <p><strong>Mensagem:</strong> <?= e($usuario['mensagem']) ?></p>
                            <?php endif; ?>
                            <p class="data">
                                <strong>Cadastrado em:</strong>
                                <?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?>
                            </p>
                        </div>

                        <div class="card-actions">
                            <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-editar">Editar</a>
                            <a href="excluir.php?id=<?= $usuario['id'] ?>"
                               class="btn btn-excluir"
                               onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                Excluir
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <section class="conceitos">
            <h2>Conceitos Utilizados</h2>
            <ul>
                <li><code>query()</code> - Executa SELECT simples</li>
                <li><code>fetchAll()</code> - Retorna todos os registros</li>
                <li><code>ORDER BY</code> - Ordena resultados</li>
                <li><code>date()</code> - Formata data para exibição</li>
                <li><code>strtotime()</code> - Converte string para timestamp</li>
            </ul>
        </section>
    </div>

</body>
</html>
