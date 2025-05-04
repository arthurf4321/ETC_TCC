-- phpMyAdmin SQL Dump
-- Banco de Dados: `sistema_acai`
-- Data de Exportação: 22/03/2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Criando o banco de dados
CREATE DATABASE IF NOT EXISTS `sistema_acai` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_acai`;

-- ===============================
-- Tabela: `clientes`
-- ===============================
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `telefone` varchar(20) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `aceite_lgpd` tinyint(1) NOT NULL DEFAULT 0,
  `data_aceite_lgpd` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Tabela: `produtos`
-- ===============================
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria` enum('açaí','adicional') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Tabela: `pedidos`
-- ===============================
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `status` enum('recebido','preparando','pronto','finalizado') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Tabela: `itens_pedido`
-- ===============================
CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL CHECK (`quantidade` > 0),
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`produto_id`) REFERENCES `produtos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Tabela: `pagamentos`
-- ===============================
CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `metodo` enum('dinheiro','cartão','pix') NOT NULL,
  `status` enum('pendente','pago','cancelado') NOT NULL,
  `data_pagamento` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Tabela: `funcionarios`
-- ===============================
CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cargo` enum('caixa','preparador','gerente') NOT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================
-- Inserindo Dados na Tabela: `funcionarios`
-- ===============================
INSERT INTO `funcionarios` (`id`, `nome`, `email`, `senha`, `cargo`, `ultimo_acesso`, `foto`) VALUES
(51, 'Marco', 'pericleschaves35@gmail.com', '$2y$10$kDN4VVEZHco9U3npwrITie68wLCRG00yGQ4U4pNsSof0/Hifh0j.e', 'gerente', '2025-03-22 08:34:22', 'default.png'),
(60, 'gustavo guanabara77', 'pericleschaves375@gmail.com', '$2y$10$SOtk/jQ7iN.GOMAVGKWEauwYGt0NtDwoZ1pFkfy/4G9TZl5oKwVom', 'preparador', '2025-03-16 09:30:19', 'default.png');

COMMIT;
