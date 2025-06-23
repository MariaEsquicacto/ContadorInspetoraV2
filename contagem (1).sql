-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2025 às 20:27
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `contagem`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(150) NOT NULL,
  `ativa_categoria` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contagem`
--

CREATE TABLE `contagem` (
  `id_contagem` int(11) NOT NULL,
  `quant_contagem` int(11) NOT NULL,
  `criacao_contagem` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `update_contagem` timestamp NULL DEFAULT NULL,
  `usuarios_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL,
  `nome_item_estoque` varchar(150) NOT NULL,
  `tipo_movimentacao_estoque` enum('entrada','saida') NOT NULL,
  `quantidade_estoque` int(11) NOT NULL,
  `unidade_estoque` enum('kg','gramas','litro','ml','unidade') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `session`
--

CREATE TABLE `session` (
  `id_session` int(11) NOT NULL,
  `token` varchar(300) NOT NULL,
  `criado_em` datetime NOT NULL,
  `expira_em` varchar(45) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `user368_id_user368` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `session`
--

INSERT INTO `session` (`id_session`, `token`, `criado_em`, `expira_em`, `status`, `user368_id_user368`) VALUES
(4, '4eb84e918c47971dbfa60d965a2724a4c4670fb169413f88113d1eee527fb8c5', '2025-05-28 15:07:25', 'nunca', '1', 6),
(5, 'a9f29d547c4701942d4cd3d07dea85b6c50d4e6e574752413c539017cb63bcab', '2025-05-30 13:09:06', 'nunca', '1', 7),
(6, '807d17be2e15c3de1fdeb0115e055c802a4e0da252a8e9dc0fc9aa381e6c260a', '2025-06-06 14:05:55', 'nunca', '1', 6),
(7, 'cf2e733b43320d73790c0f1d9c8e5ef060de59ee1e21a3aa0fdf327a05d8eae4', '2025-06-11 11:34:28', 'nunca', '1', 8),
(8, '3ce39d3abe47518cdba1d3d49b3e3e96bb765a022a4d40540220d70ff8dd6455', '2025-06-11 11:51:27', 'nunca', '1', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id_turma` int(11) NOT NULL,
  `nome_turma` varchar(150) NOT NULL,
  `categorias_id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(150) NOT NULL,
  `senha_usuario` varchar(150) NOT NULL,
  `nivel_usuario` enum('1','2','3') NOT NULL,
  `ativo_usuario` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome_usuario`, `senha_usuario`, `nivel_usuario`, `ativo_usuario`) VALUES
(4, 'Eric', '$2y$10$X29l0sgqRgZEK0ay2GYYXueRKsQjOyAGeEsmpVtFNesIGaOqQhc9S', '1', 1),
(5, 'Arthut', '$2y$10$Y3PzsSUmXGBI8JSM5lIzvu249KR7FPxYc9TScVCv5p3d9.oMt33A6', '1', 1),
(6, 'Maria Clara', '$2y$10$uOU8buQSFyCYOfzSz3flBOwzg5bbM9c1QgkLDzmWWofRxNALHrbAC', '1', 1),
(7, 'Andre', '$2y$10$GwB4bXQ8ADPN3sUjtLuDpOOL6KqLMO1iyL8Z5v.jnCK.d2JK.mRGC', '1', 1),
(8, 'Arthur', '$2y$10$edF/nlsN23E8Z04V700vZOJ2UQnXXApvy.6xxXcS/P7XxWtV7qtkm', '1', 1),
(9, 'dani', '$2y$10$1Q6q96YWpLVKrJZ3Pwjunujnd2tgWOZxMoJ6Hds1GiR8s6Kh/mgJu', '1', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `contagem`
--
ALTER TABLE `contagem`
  ADD PRIMARY KEY (`id_contagem`),
  ADD KEY `fk_contagem_usuarios_idx` (`usuarios_id_usuario`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_estoque`);

--
-- Índices de tabela `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id_session`),
  ADD KEY `user368_id_user368` (`user368_id_user368`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id_turma`),
  ADD KEY `fk_turmas_categorias1_idx` (`categorias_id_categoria`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contagem`
--
ALTER TABLE `contagem`
  MODIFY `id_contagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `session`
--
ALTER TABLE `session`
  MODIFY `id_session` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `contagem`
--
ALTER TABLE `contagem`
  ADD CONSTRAINT `fk_contagem_usuarios` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`user368_id_user368`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `fk_turmas_categorias1` FOREIGN KEY (`categorias_id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
