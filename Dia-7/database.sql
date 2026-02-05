-- ============================================
-- Dia 7 - Script de Criação do Banco de Dados
-- ============================================
-- Execute este script no phpMyAdmin ou MySQL CLI
-- para criar o banco e a tabela de usuários

-- Cria o banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS estudo_backend
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco
USE estudo_backend;

-- Cria a tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    idade INT NOT NULL,
    senha VARCHAR(255) NOT NULL,
    mensagem TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índice para buscas por email
CREATE INDEX idx_email ON usuarios(email);

-- Dados de exemplo (opcional)
-- INSERT INTO usuarios (nome, email, idade, senha, mensagem) VALUES
-- ('João Silva', 'joao@email.com', 25, '$2y$10$hash_aqui', 'Olá, sou o João!'),
-- ('Maria Santos', 'maria@email.com', 30, '$2y$10$hash_aqui', 'Prazer em conhecer!');
