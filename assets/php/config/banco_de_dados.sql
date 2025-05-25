-- phpMyAdmin SQL Dump Atualizado com Relacionamentos de Funcionário
-- Banco de dados: `sistema_acai`

CREATE DATABASE IF NOT EXISTS `sistema_acai` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_acai`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

-- Tabela: clientes
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `aceite_lgpd` tinyint(1) NOT NULL DEFAULT 0,
  `data_aceite_lgpd` datetime DEFAULT current_timestamp(),
  `cadastrado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Tabela: funcionarios
CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cargo` enum('caixa','preparador','gerente') NOT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dados de exemplo para funcionarios
INSERT INTO `funcionarios` (`id`, `nome`, `email`, `senha`, `cargo`, `ultimo_acesso`, `foto`) VALUES
(61, 'Joao', 'Gerente@gmail.com', '$2y$10$Ty58ViOit83Z4x7C542QG.VfW7UK0Fl61M9yOl2CDRwlmz2Na3naa', 'gerente', '2025-05-11 18:51:35', 'default.png'),
(62, 'Beatriz', 'Caixa@gmail.com', '$2y$10$uXUUUG09mTYejbZ4LzBpNeSsVjKcHTa7M3C4xziXuZRBC8RKByI2e', 'caixa', '2025-05-11 18:51:50', 'default.png'),
(63, 'Paulo', 'Preparador@gmail.com', '$2y$10$8IMmSB.osFQG27tF3sNjO.jWNiaw0kxK8/tT7URyjeFeP7IESOWcu', 'preparador', '2025-05-11 18:51:25', 'default.png');

-- --------------------------------------------------------

-- Tabela: itens_pedido
CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL CHECK (`quantidade` > 0),
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Tabela: pagamentos
CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `metodo` enum('dinheiro','cartão','pix') NOT NULL,
  `status` enum('pendente','pago','cancelado') NOT NULL,
  `data_pagamento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Tabela: pedidos
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `status` enum('recebido','preparando','pronto','finalizado') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `registrado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Tabela: produtos
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria` enum('açaí','adicional') NOT NULL,
  `criado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Produtos de exemplo
INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `categoria`) VALUES
(1, 'açai 300ml', 'Açaí tradicional na tigela de 300ml', 10.00, 'açaí'),
(2, 'açai 500ml', 'Açaí tradicional na tigela de 500ml', 15.00, 'açaí'),
(3, 'açai 700ml', 'Açaí tradicional na tigela de 700ml', 20.00, 'açaí');

-- --------------------------------------------------------

-- Índices
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `cadastrado_por` (`cadastrado_por`);

ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `registrado_por` (`registrado_por`);

ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criado_por` (`criado_por`);

-- Auto Increment
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- Relacionamentos (Foreign Keys)
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_funcionario`
  FOREIGN KEY (`cadastrado_por`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedidos_funcionario`
  FOREIGN KEY (`registrado_por`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_funcionario`
  FOREIGN KEY (`criado_por`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
