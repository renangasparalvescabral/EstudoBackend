<?php
/**
 * Dia 8 - Logout
 *
 * Encerra a sessão e redireciona para login
 */

require_once 'auth.php';

logout();

// Mensagem de despedida
session_start();
$_SESSION['mensagem'] = 'Você saiu com sucesso. Até logo!';
$_SESSION['mensagem_tipo'] = 'sucesso';

header('Location: login.php');
exit;
