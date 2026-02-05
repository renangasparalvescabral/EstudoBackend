<?php
/**
 * Dia 6 - PHP Básico: Formulários e Validação Server-Side
 *
 * Conceitos abordados:
 * - $_POST para receber dados do formulário
 * - Validação de campos obrigatórios
 * - Validação de formato (email, números)
 * - Sanitização de dados (htmlspecialchars, trim)
 * - Exibição de erros
 * - Manter valores preenchidos após erro
 */

// Inicializa variáveis
$erros = [];
$sucesso = false;
$dados = [
    'nome' => '',
    'email' => '',
    'idade' => '',
    'senha' => '',
    'mensagem' => ''
];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. RECEBER E SANITIZAR DADOS
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['idade'] = trim($_POST['idade'] ?? '');
    $dados['senha'] = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    $dados['mensagem'] = trim($_POST['mensagem'] ?? '');

    // 2. VALIDAÇÕES

    // Nome: obrigatório, mínimo 3 caracteres
    if (empty($dados['nome'])) {
        $erros['nome'] = 'O nome é obrigatório';
    } elseif (strlen($dados['nome']) < 3) {
        $erros['nome'] = 'O nome deve ter pelo menos 3 caracteres';
    } elseif (strlen($dados['nome']) > 100) {
        $erros['nome'] = 'O nome deve ter no máximo 100 caracteres';
    }

    // Email: obrigatório, formato válido
    if (empty($dados['email'])) {
        $erros['email'] = 'O email é obrigatório';
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Digite um email válido';
    }

    // Idade: obrigatório, número entre 18 e 120
    if (empty($dados['idade'])) {
        $erros['idade'] = 'A idade é obrigatória';
    } elseif (!is_numeric($dados['idade'])) {
        $erros['idade'] = 'A idade deve ser um número';
    } elseif ($dados['idade'] < 18 || $dados['idade'] > 120) {
        $erros['idade'] = 'A idade deve ser entre 18 e 120 anos';
    }

    // Senha: obrigatório, mínimo 6 caracteres
    if (empty($dados['senha'])) {
        $erros['senha'] = 'A senha é obrigatória';
    } elseif (strlen($dados['senha']) < 6) {
        $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres';
    }

    // Confirmar senha: deve ser igual à senha
    if ($dados['senha'] !== $confirmarSenha) {
        $erros['confirmar_senha'] = 'As senhas não conferem';
    }

    // Mensagem: opcional, máximo 500 caracteres
    if (strlen($dados['mensagem']) > 500) {
        $erros['mensagem'] = 'A mensagem deve ter no máximo 500 caracteres';
    }

    // 3. SE NÃO HOUVER ERROS, PROCESSA
    if (empty($erros)) {
        $sucesso = true;

        // Aqui você pode:
        // - Salvar no banco de dados
        // - Enviar email
        // - Criar sessão de usuário
        // Por enquanto, apenas exibimos os dados

        // Limpa os campos após sucesso
        $dadosEnviados = $dados;
        $dados = [
            'nome' => '',
            'email' => '',
            'idade' => '',
            'senha' => '',
            'mensagem' => ''
        ];
    }
}

// Função helper para exibir valores com segurança
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dia 6 - PHP Básico</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Dia 6 - Formulários e Validação PHP</h1>
        <p class="subtitulo">Validação server-side com PHP</p>

        <?php if ($sucesso): ?>
            <div class="alerta sucesso">
                <h3>Cadastro realizado com sucesso!</h3>
                <p>Dados recebidos:</p>
                <ul>
                    <li><strong>Nome:</strong> <?= e($dadosEnviados['nome']) ?></li>
                    <li><strong>Email:</strong> <?= e($dadosEnviados['email']) ?></li>
                    <li><strong>Idade:</strong> <?= e($dadosEnviados['idade']) ?> anos</li>
                    <li><strong>Mensagem:</strong> <?= e($dadosEnviados['mensagem']) ?: '(não informada)' ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <h3>Ops! Corrija os erros abaixo:</h3>
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= e($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" novalidate>

            <div class="campo <?= isset($erros['nome']) ? 'campo-erro' : '' ?>">
                <label for="nome">Nome *</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= e($dados['nome']) ?>"
                    placeholder="Seu nome completo"
                >
                <?php if (isset($erros['nome'])): ?>
                    <span class="msg-erro"><?= e($erros['nome']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo <?= isset($erros['email']) ? 'campo-erro' : '' ?>">
                <label for="email">Email *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($dados['email']) ?>"
                    placeholder="seu@email.com"
                >
                <?php if (isset($erros['email'])): ?>
                    <span class="msg-erro"><?= e($erros['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo <?= isset($erros['idade']) ? 'campo-erro' : '' ?>">
                <label for="idade">Idade *</label>
                <input
                    type="number"
                    id="idade"
                    name="idade"
                    value="<?= e($dados['idade']) ?>"
                    placeholder="Sua idade"
                    min="18"
                    max="120"
                >
                <?php if (isset($erros['idade'])): ?>
                    <span class="msg-erro"><?= e($erros['idade']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo <?= isset($erros['senha']) ? 'campo-erro' : '' ?>">
                <label for="senha">Senha *</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Mínimo 6 caracteres"
                >
                <?php if (isset($erros['senha'])): ?>
                    <span class="msg-erro"><?= e($erros['senha']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo <?= isset($erros['confirmar_senha']) ? 'campo-erro' : '' ?>">
                <label for="confirmar_senha">Confirmar Senha *</label>
                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="Repita a senha"
                >
                <?php if (isset($erros['confirmar_senha'])): ?>
                    <span class="msg-erro"><?= e($erros['confirmar_senha']) ?></span>
                <?php endif; ?>
            </div>

            <div class="campo <?= isset($erros['mensagem']) ? 'campo-erro' : '' ?>">
                <label for="mensagem">Mensagem (opcional)</label>
                <textarea
                    id="mensagem"
                    name="mensagem"
                    placeholder="Escreva uma mensagem..."
                    rows="4"
                ><?= e($dados['mensagem']) ?></textarea>
                <span class="contador-chars">
                    <span id="charCount"><?= strlen($dados['mensagem']) ?></span>/500
                </span>
                <?php if (isset($erros['mensagem'])): ?>
                    <span class="msg-erro"><?= e($erros['mensagem']) ?></span>
                <?php endif; ?>
            </div>

            <p class="obrigatorio">* Campos obrigatórios</p>

            <button type="submit" class="btn-enviar">Cadastrar</button>
        </form>

        <section class="conceitos">
            <h2>Conceitos Utilizados</h2>
            <ul>
                <li><code>$_POST</code> - Recebe dados enviados via método POST</li>
                <li><code>$_SERVER['REQUEST_METHOD']</code> - Verifica o método HTTP</li>
                <li><code>trim()</code> - Remove espaços do início e fim</li>
                <li><code>empty()</code> - Verifica se está vazio</li>
                <li><code>strlen()</code> - Conta caracteres</li>
                <li><code>filter_var()</code> - Valida formato (email, URL, etc)</li>
                <li><code>is_numeric()</code> - Verifica se é número</li>
                <li><code>htmlspecialchars()</code> - Previne XSS</li>
            </ul>
        </section>
    </div>

    <script>
        // Contador de caracteres da mensagem
        const mensagem = document.getElementById('mensagem');
        const charCount = document.getElementById('charCount');

        mensagem.addEventListener('input', () => {
            charCount.textContent = mensagem.value.length;
        });
    </script>
</body>
</html>
