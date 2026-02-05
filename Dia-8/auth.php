<?php
/**
 * Dia 8 - Funções de Autenticação
 *
 * Conceitos:
 * - $_SESSION para armazenar dados do usuário logado
 * - password_verify() para verificar senha
 * - Regeneração de ID de sessão para segurança
 */

require_once 'config.php';

/**
 * Verifica se o usuário está logado
 */
function estaLogado(): bool
{
    return isset($_SESSION['usuario_id']);
}

/**
 * Obtém dados do usuário logado
 */
function getUsuarioLogado(): ?array
{
    if (!estaLogado()) {
        return null;
    }

    return [
        'id' => $_SESSION['usuario_id'],
        'nome' => $_SESSION['usuario_nome'],
        'email' => $_SESSION['usuario_email']
    ];
}

/**
 * Redireciona para login se não estiver autenticado
 */
function exigirLogin(): void
{
    if (!estaLogado()) {
        $_SESSION['mensagem'] = 'Você precisa fazer login para acessar esta página.';
        $_SESSION['mensagem_tipo'] = 'erro';
        header('Location: login.php');
        exit;
    }
}

/**
 * Redireciona para dashboard se já estiver logado
 */
function redirecionarSeLogado(): void
{
    if (estaLogado()) {
        header('Location: dashboard.php');
        exit;
    }
}

/**
 * Realiza o login do usuário
 */
function login(string $email, string $senha): array
{
    $resultado = [
        'sucesso' => false,
        'erro' => ''
    ];

    if (empty($email) || empty($senha)) {
        $resultado['erro'] = 'Preencha todos os campos';
        return $resultado;
    }

    try {
        $pdo = conectar();

        // Busca usuário pelo email
        $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $resultado['erro'] = 'Email ou senha incorretos';
            return $resultado;
        }

        // Verifica a senha
        if (!password_verify($senha, $usuario['senha'])) {
            $resultado['erro'] = 'Email ou senha incorretos';
            return $resultado;
        }

        // Login bem-sucedido!
        // Regenera ID da sessão para prevenir session fixation
        session_regenerate_id(true);

        // Armazena dados na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['login_time'] = time();

        $resultado['sucesso'] = true;

    } catch (PDOException $e) {
        $resultado['erro'] = 'Erro no servidor. Tente novamente.';
    }

    return $resultado;
}

/**
 * Realiza o logout
 */
function logout(): void
{
    // Limpa todas as variáveis de sessão
    $_SESSION = [];

    // Destrói o cookie da sessão
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destrói a sessão
    session_destroy();
}

/**
 * Registra um novo usuário
 */
function registrar(array $dados): array
{
    $resultado = [
        'sucesso' => false,
        'erros' => []
    ];

    // Validações
    if (empty($dados['nome']) || strlen($dados['nome']) < 3) {
        $resultado['erros']['nome'] = 'Nome deve ter pelo menos 3 caracteres';
    }

    if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $resultado['erros']['email'] = 'Email inválido';
    }

    if (empty($dados['senha']) || strlen($dados['senha']) < 6) {
        $resultado['erros']['senha'] = 'Senha deve ter pelo menos 6 caracteres';
    }

    if ($dados['senha'] !== $dados['confirmar_senha']) {
        $resultado['erros']['confirmar_senha'] = 'As senhas não conferem';
    }

    if (!empty($resultado['erros'])) {
        return $resultado;
    }

    try {
        $pdo = conectar();

        // Verifica se email já existe
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$dados['email']]);

        if ($stmt->fetch()) {
            $resultado['erros']['email'] = 'Este email já está cadastrado';
            return $resultado;
        }

        // Insere o usuário
        $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (nome, email, idade, senha) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['idade'] ?? 18,
            $senhaHash
        ]);

        $resultado['sucesso'] = true;
        $resultado['usuario_id'] = $pdo->lastInsertId();

    } catch (PDOException $e) {
        $resultado['erros']['banco'] = 'Erro ao cadastrar. Tente novamente.';
    }

    return $resultado;
}
