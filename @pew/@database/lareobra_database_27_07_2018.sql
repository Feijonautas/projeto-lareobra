-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql103.prv.f1.k8.com.br.
-- Tempo de geração: 27/07/2018 às 15:48
-- Versão do servidor: 10.1.25-MariaDB-1~xenial
-- Versão do PHP: 7.0.31-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lareobra1`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `franquias_lojas`
--

CREATE TABLE `franquias_lojas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `proprietario` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cep` varchar(11) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `cep_inicial` varchar(11) NOT NULL,
  `cep_final` varchar(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `franquias_newsletter`
--

CREATE TABLE `franquias_newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `cep` varchar(11) NOT NULL,
  `data_cadastro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `franquias_produtos`
--

CREATE TABLE `franquias_produtos` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `preco_bruto` varchar(255) NOT NULL,
  `preco_promocao` varchar(255) NOT NULL,
  `promocao_ativa` int(11) NOT NULL,
  `estoque` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `franquias_requisicoes`
--

CREATE TABLE `franquias_requisicoes` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `info_produtos` text NOT NULL,
  `estoque_adicionado` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_banners`
--

CREATE TABLE `pew_banners` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `posicao` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_carrinhos`
--

CREATE TABLE `pew_carrinhos` (
  `id` int(11) NOT NULL,
  `token_carrinho` varchar(255) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `quantidade_produto` int(11) NOT NULL,
  `preco_produto` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_categorias`
--

CREATE TABLE `pew_categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_categorias`
--

INSERT INTO `pew_categorias` (`id`, `categoria`, `descricao`, `ref`, `data_controle`, `status`) VALUES
(17, 'DecoraÃ§Ã£o ', '', 'decoracao-', '2018-07-20 10:33:09', 1),
(18, 'Eletro e EletrÃ´nicos', '', 'eletro-e-eletronicos', '2018-04-25 03:13:23', 1),
(19, 'InformÃ¡tica e Papelaria', '', 'informatica-e-papelaria', '2018-04-25 03:13:38', 1),
(20, 'Automotivo', '', 'automotivo', '2018-04-25 05:30:17', 1),
(21, 'ElÃ©trica', '', 'eletrica', '2018-04-25 03:14:12', 1),
(22, 'Ferragens', '', 'ferragens', '2018-04-25 03:14:22', 1),
(23, 'HidrÃ¡ulica', '', 'hidraulica', '2018-04-25 03:14:47', 1),
(24, 'IluminaÃ§Ã£o', '', 'iluminacao', '2018-04-25 03:15:02', 1),
(25, 'Chuveiros, Aquecedores e GÃ¡s', '', 'chuveiros-aquecedores-e-gas', '2018-04-26 03:19:22', 1),
(26, 'Utilidades do Lar', '', 'utilidades-do-lar', '2018-04-26 03:19:22', 1),
(27, 'Lavanderia e Banheiro', '', 'lavanderia-e-banheiro', '2018-04-26 03:19:22', 1),
(28, 'Tecnologia', '', 'tecnologia', '2018-04-26 03:19:22', 1),
(29, 'Banheiro', '', 'banheiro', '2018-04-26 03:19:22', 1),
(30, 'Tintas e AcessÃ³rios', '', 'tintas-e-acessorios', '2018-04-26 03:19:22', 1),
(31, 'Diversos', '', 'diversos', '2018-04-26 03:19:22', 1),
(32, 'Colas, Adesivos e Lubrificantes', '', 'colas-adesivos-e-lubrificantes', '2018-04-26 03:19:22', 1),
(33, 'Material de ContruÃ§Ã£o', '', 'material-de-contrucao', '2018-04-26 03:19:22', 1),
(34, 'Utilidades Pessoais', '', 'utilidades-pessoais', '2018-04-26 03:19:22', 1),
(35, 'Fechaduras e SeguranÃ§a', '', 'fechaduras-e-seguranca', '2018-04-26 03:19:22', 1),
(36, 'OrganizaÃ§Ã£o e Bricolagem', '', 'organizacao-e-bricolagem', '2018-04-26 03:19:22', 1),
(37, 'Petshop', '', 'petshop', '2018-04-26 03:19:22', 1),
(38, 'Lazer, Esporte e Camping', '', 'lazer-esporte-e-camping', '2018-04-26 03:19:22', 1),
(39, 'QuÃ­micos, Limpeza e Piscina', '', 'quimicos-limpeza-e-piscina', '2018-04-26 03:19:22', 1),
(40, 'Bike', '', 'bike', '2018-04-26 03:19:22', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_categorias_produtos`
--

CREATE TABLE `pew_categorias_produtos` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_categorias_produtos`
--

INSERT INTO `pew_categorias_produtos` (`id`, `id_produto`, `id_categoria`) VALUES
(45, 2, 25),
(46, 3, 24),
(47, 4, 26),
(48, 5, 24),
(49, 7, 27),
(50, 8, 24),
(51, 10, 25),
(52, 11, 21),
(53, 12, 18),
(54, 13, 28),
(55, 16, 22),
(56, 17, 29),
(57, 18, 25),
(58, 19, 25),
(59, 20, 24),
(60, 21, 24),
(61, 22, 19),
(62, 23, 29),
(63, 24, 25),
(64, 25, 30),
(65, 26, 30),
(66, 27, 30),
(67, 28, 31),
(68, 29, 30),
(69, 30, 30),
(70, 31, 30),
(71, 35, 30),
(72, 36, 18),
(73, 39, 26),
(74, 40, 26),
(75, 41, 26),
(76, 42, 26),
(77, 43, 26),
(78, 44, 26),
(79, 45, 26),
(80, 47, 26),
(81, 48, 26),
(82, 49, 26),
(83, 50, 26),
(84, 51, 26),
(85, 52, 26),
(86, 53, 26),
(87, 54, 26),
(88, 55, 26),
(89, 56, 26),
(90, 58, 32),
(91, 59, 26),
(92, 60, 33),
(93, 61, 33),
(94, 62, 22),
(95, 63, 22),
(96, 64, 22),
(97, 65, 22),
(98, 66, 22),
(99, 67, 22),
(100, 68, 33),
(101, 69, 33),
(102, 70, 30),
(103, 71, 17),
(104, 72, 32),
(105, 73, 20),
(106, 74, 18),
(107, 75, 18),
(108, 76, 18),
(109, 77, 34),
(110, 78, 18),
(111, 79, 18),
(112, 80, 26),
(113, 81, 18),
(114, 82, 18),
(115, 83, 17),
(116, 84, 26),
(117, 85, 26),
(118, 86, 26),
(119, 87, 26),
(120, 88, 26),
(121, 89, 30),
(122, 90, 30),
(123, 91, 30),
(124, 92, 30),
(125, 93, 35),
(126, 94, 35),
(127, 95, 35),
(128, 96, 35),
(129, 97, 35),
(130, 98, 35),
(131, 99, 35),
(132, 100, 27),
(133, 101, 34),
(134, 102, 26),
(135, 103, 17),
(136, 104, 20),
(137, 105, 20),
(138, 106, 20),
(139, 107, 20),
(140, 108, 20),
(141, 109, 20),
(142, 110, 24),
(143, 111, 17),
(144, 112, 17),
(145, 113, 18),
(146, 114, 36),
(147, 115, 36),
(148, 116, 36),
(149, 117, 32),
(150, 118, 32),
(151, 119, 37),
(152, 120, 37),
(153, 121, 37),
(154, 122, 37),
(155, 123, 37),
(156, 124, 37),
(157, 125, 32),
(158, 126, 17),
(159, 127, 38),
(160, 128, 38),
(161, 129, 36),
(162, 130, 32),
(163, 131, 18),
(164, 132, 18),
(165, 133, 26),
(166, 134, 26),
(168, 136, 26),
(169, 137, 26),
(170, 138, 26),
(171, 139, 39),
(172, 140, 20),
(173, 141, 21),
(174, 142, 40),
(175, 143, 40),
(176, 144, 40),
(177, 145, 40),
(178, 146, 40),
(179, 147, 40),
(180, 148, 40),
(181, 149, 40),
(182, 150, 39),
(183, 151, 23),
(184, 152, 23),
(185, 153, 23),
(186, 154, 23),
(187, 155, 23),
(188, 156, 23),
(189, 157, 23),
(190, 158, 23),
(191, 159, 23),
(192, 160, 23),
(193, 161, 23),
(194, 164, 24),
(195, 165, 26),
(196, 166, 34);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_categorias_vitrine`
--

CREATE TABLE `pew_categorias_vitrine` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_categoria_destaque`
--

CREATE TABLE `pew_categoria_destaque` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_config_orcamentos`
--

CREATE TABLE `pew_config_orcamentos` (
  `id` int(11) NOT NULL,
  `nome_empresa` varchar(255) NOT NULL,
  `cnpj_empresa` varchar(255) NOT NULL,
  `endereco_empresa` varchar(255) NOT NULL,
  `telefone_empresa` varchar(255) NOT NULL,
  `email_contato` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_contatos`
--

CREATE TABLE `pew_contatos` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `mensagem` longtext NOT NULL,
  `data` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_contatos`
--

INSERT INTO `pew_contatos` (`id`, `id_franquia`, `nome`, `email`, `telefone`, `assunto`, `mensagem`, `data`, `status`) VALUES
(6, 11, 'Rogerio Mendes', 'reyrogerio@hotmail.com', '(41) 99753-6262', 'SugestÃµes', 'OlÃ¡, estou enviando uma mensagem teste.', '2018-07-24 11:28:02', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_contatos_servicos`
--

CREATE TABLE `pew_contatos_servicos` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_cores`
--

CREATE TABLE `pew_cores` (
  `id` int(11) NOT NULL,
  `cor` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_cores`
--

INSERT INTO `pew_cores` (`id`, `cor`, `imagem`, `data_controle`, `status`) VALUES
(27, 'Branco', '3605-ref3605.png', '2018-04-25 03:50:38', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_cores_relacionadas`
--

CREATE TABLE `pew_cores_relacionadas` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `id_relacao` int(11) DEFAULT NULL,
  `data_controle` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_departamentos`
--

CREATE TABLE `pew_departamentos` (
  `id` int(11) NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `posicao` int(11) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_controle` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `pew_departamentos`
--

INSERT INTO `pew_departamentos` (`id`, `departamento`, `descricao`, `posicao`, `ref`, `imagem`, `data_controle`, `status`) VALUES
(26, 'PRODUTOS PARA LAR', '', 0, 'produtos-para-lar', 'produtos-para-lar-departamento.png', '2018-05-14', 1),
(27, 'PRODUTOS PARA OBRA', '', 0, 'produtos-para-obra', 'produtos-para-obra-departamento.png', '2018-05-14', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_departamentos_produtos`
--

CREATE TABLE `pew_departamentos_produtos` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `pew_departamentos_produtos`
--

INSERT INTO `pew_departamentos_produtos` (`id`, `id_produto`, `id_departamento`) VALUES
(157, 2, 26),
(158, 3, 27),
(159, 4, 27),
(160, 5, 26),
(161, 7, 26),
(162, 8, 27),
(163, 10, 27),
(164, 11, 27),
(360, 12, 26),
(166, 13, 26),
(168, 17, 26),
(169, 18, 26),
(170, 19, 26),
(171, 20, 26),
(172, 21, 26),
(173, 22, 26),
(174, 23, 26),
(175, 24, 26),
(176, 25, 27),
(177, 26, 27),
(178, 27, 27),
(179, 28, 26),
(180, 29, 27),
(181, 30, 27),
(182, 31, 27),
(183, 35, 27),
(184, 36, 26),
(185, 39, 26),
(186, 40, 26),
(187, 41, 26),
(188, 42, 26),
(189, 43, 26),
(190, 44, 26),
(191, 45, 26),
(192, 47, 26),
(193, 48, 26),
(194, 49, 26),
(195, 50, 26),
(196, 51, 26),
(197, 52, 26),
(198, 53, 26),
(199, 54, 26),
(200, 55, 26),
(201, 56, 26),
(202, 58, 27),
(203, 59, 26),
(204, 60, 27),
(205, 61, 27),
(206, 62, 27),
(207, 63, 27),
(208, 64, 27),
(209, 65, 27),
(210, 66, 27),
(211, 67, 27),
(212, 68, 27),
(213, 69, 27),
(214, 70, 27),
(215, 71, 26),
(216, 72, 27),
(217, 73, 26),
(218, 74, 26),
(219, 75, 26),
(220, 76, 26),
(221, 77, 26),
(357, 78, 26),
(340, 79, 26),
(224, 80, 26),
(225, 81, 26),
(226, 82, 26),
(227, 83, 26),
(228, 84, 26),
(229, 85, 26),
(230, 86, 26),
(231, 87, 26),
(232, 88, 26),
(233, 89, 27),
(234, 90, 27),
(235, 91, 27),
(236, 92, 27),
(237, 93, 27),
(238, 94, 26),
(239, 95, 27),
(240, 96, 27),
(241, 97, 27),
(242, 98, 27),
(243, 99, 27),
(244, 100, 26),
(245, 101, 26),
(246, 102, 26),
(247, 103, 26),
(248, 104, 26),
(249, 105, 26),
(250, 106, 26),
(251, 107, 26),
(252, 108, 26),
(253, 109, 26),
(254, 110, 27),
(255, 111, 26),
(256, 112, 26),
(257, 113, 26),
(258, 114, 26),
(259, 115, 26),
(260, 116, 26),
(261, 117, 27),
(262, 118, 27),
(263, 119, 26),
(264, 120, 26),
(265, 121, 26),
(266, 122, 26),
(267, 123, 26),
(268, 124, 26),
(269, 125, 26),
(270, 126, 26),
(271, 127, 26),
(272, 128, 26),
(273, 129, 27),
(274, 130, 26),
(275, 131, 26),
(276, 132, 26),
(277, 133, 26),
(278, 134, 26),
(279, 135, 26),
(280, 136, 26),
(281, 137, 26),
(282, 138, 26),
(283, 139, 26),
(284, 140, 26),
(285, 141, 26),
(286, 142, 26),
(287, 143, 26),
(288, 144, 26),
(289, 145, 26),
(290, 146, 26),
(291, 147, 26),
(292, 148, 26),
(293, 149, 26),
(294, 150, 26),
(298, 151, 27),
(297, 152, 27),
(299, 153, 27),
(300, 154, 27),
(301, 155, 27),
(302, 156, 27),
(303, 157, 27),
(304, 158, 27),
(305, 159, 27),
(345, 160, 27),
(331, 161, 27),
(354, 166, 26),
(353, 165, 26),
(352, 164, 26),
(351, 163, 26),
(350, 162, 26);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_dicas`
--

CREATE TABLE `pew_dicas` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `subtitulo` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `descricao_curta` varchar(255) DEFAULT NULL,
  `descricao_longa` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `data_controle` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_dicas`
--

INSERT INTO `pew_dicas` (`id`, `id_franquia`, `titulo`, `subtitulo`, `ref`, `descricao_curta`, `descricao_longa`, `imagem`, `thumb`, `video`, `data_controle`, `status`) VALUES
(2, 11, '5 Dicas para acampar pela primeira vez', 'Saber o que levar para acampar Ã© muito importante', '5-dicas-para-acampar-pela-primeira-vez', 'O camping  vai nortear o futuro dos seus prÃ³ximos passos durante os dias. Antes de sair para acampar veja estas 5 dicas.', '<p><strong>1. Saiba escolher um bom lugar</strong></p><p>O camping &nbsp;vai nortear o futuro dos seus pr&oacute;ximos passos durante os dias. Antes de decidir o local veja se existe: uma estrutura b&aacute;sica (banheiros com &aacute;gua quente, cozinha ou restaurante pr&oacute;ximo, locais bons para colocar sua barraca, pontos de energia e f&aacute;cil deslocamento). A n&atilde;o ser que voc&ecirc; escolha uma grande aventura em pontos mais desconhecidos, essas s&atilde;o regras b&aacute;sicas para sair de casa.&nbsp;</p><p><strong>2. Sua casa, sua vida: a barraca</strong></p><p>Sua casinha tempor&aacute;ria precisa ser um bom investimento. Fa&ccedil;a chuva ou fa&ccedil;a sol, barracas mais b&aacute;sicas n&atilde;o est&atilde;o preparadas para tal situa&ccedil;&atilde;o. Escolha preferencialmente modelos imperme&aacute;veis. Confira o tamanho, barracas para 2 ou 3 pessoas geralmente n&atilde;o comportam esse volume se voc&ecirc; carrega muita bagagem. Pense o tempo todo no seu conforto, com certeza pre&ccedil;os baixos n&atilde;o ter&atilde;o o retorno que voc&ecirc; espera. N&atilde;o se esque&ccedil;a do colch&atilde;o de ar ou saco de dormir, eles ser&atilde;o o seu conforto dos pr&oacute;ximos dias. Se voc&ecirc; precisa caminhar muito ou fazer alguma trilha confira se ela &eacute; leve.&nbsp;</p><p><strong>3. Itens indispens&aacute;veis</strong></p><p>A&iacute; v&atilde;o eles: bateria recarreg&aacute;vel para aparelhos eletr&ocirc;nicos, isso inclui celulares, r&aacute;dios, lanterna entre outros. Ali&aacute;s, a lanterna &eacute; outro item que n&atilde;o pode ficar de fora da sua listinha, ela pode salvar sua vida em v&aacute;rios momentos. Sacos de lixo, repelente e papel higi&ecirc;nico tamb&eacute;m precisam estar em dia na sua mala. Outra coisa que sempre bom ter isqueiro e um kit de primeiros socorros. Carregue roupas leves e confort&aacute;veis, voc&ecirc; n&atilde;o est&aacute; na cidade.&nbsp;</p><p><strong>4. Atividades</strong></p><p>Para realizar suas aventuras por a&iacute; &eacute; necess&aacute;rio conhecer onde est&aacute; indo. Procure recomenda&ccedil;&otilde;es, ande sempre com um mapa do local ou procure um guia para n&atilde;o se meter em enrascadas, afinal, voc&ecirc; est&aacute; na natureza. Tome cuidado com as trilhas e certifique-se que ela &eacute; um lugar adequado e j&aacute; explorado, a natureza pode ser trai&ccedil;oeira. N&atilde;o se esque&ccedil;a de conferir a previs&atilde;o do tempo, ningu&eacute;m merece fazer todo o deslocamento para outro lugar para tomar muita chuva, por exemplo.&nbsp;</p><p><strong>5. Companhia</strong></p><p>Muitas pessoas costumam ir pela primeira vez em um acampamento e acabam surpresas com o que d&aacute; para ser feito por l&aacute;, ou seja, amam ou odeiam. Esteja certo de que voc&ecirc; e sua companhia n&atilde;o estar&atilde;o no conforto do lar, mas v&aacute;rias experi&ecirc;ncias extremamente &uacute;nicas podem ser vivenciadas durante a estadia.</p><p>Preparado para se aventurar? Temos certeza que uma ida at&eacute; a natureza n&atilde;o far&aacute; mal, ao contr&aacute;rio, trar&aacute; novas emo&ccedil;&otilde;es para a sua vida.O camping &eacute; um &oacute;timo momento para fazer novos amigos j&aacute; que tamb&eacute;m &eacute; uma experi&ecirc;ncia de viver em comunidade com poucos recursos. Aproveite!</p>', '', '', '', '2018-07-19 04:25:26', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_enderecos`
--

CREATE TABLE `pew_enderecos` (
  `id` int(11) NOT NULL,
  `id_relacionado` int(11) NOT NULL,
  `ref_relacionado` int(11) NOT NULL,
  `cep` int(8) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_enderecos`
--

INSERT INTO `pew_enderecos` (`id`, `id_relacionado`, `ref_relacionado`, `cep`, `rua`, `numero`, `complemento`, `bairro`, `estado`, `cidade`, `data_cadastro`, `data_controle`, `status`) VALUES
(61, 68, 1, 80230040, 'Rua Engenheiros RebouÃ§as', '2111', 'Apto 06', 'RebouÃ§as', 'PR', 'Curitiba', '2018-06-05 04:12:05', '2018-06-05 04:12:05', 1),
(62, 69, 1, 80230040, 'Rua Engenheiros RebouÃ§as', '2111', 'Apto 06', 'RebouÃ§as', 'PR', 'Curitiba', '2018-06-28 04:11:36', '2018-06-28 04:11:36', 1),
(63, 70, 1, 80230040, 'Rua Engenheiros RebouÃ§as', '2111', 'ap 7', 'RebouÃ§as', 'PR', 'Curitiba', '2018-06-28 05:27:18', '2018-06-28 05:27:18', 1),
(64, 71, 1, 81200490, 'Rua Doutor Edemar Ernsen', '245', '', 'Campo Comprido', 'PR', 'Curitiba', '2018-07-24 12:04:12', '2018-07-24 12:04:12', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_especificacoes_produtos`
--

CREATE TABLE `pew_especificacoes_produtos` (
  `id` int(11) NOT NULL,
  `id_especificacao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `descricao` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `pew_especificacoes_produtos`
--

INSERT INTO `pew_especificacoes_produtos` (`id`, `id_especificacao`, `id_produto`, `descricao`) VALUES
(102, 13, 22, 'MÃ©dia'),
(103, 12, 22, 'Caramelo'),
(104, 10, 22, 'Couro'),
(112, 10, 25, 'Plastico'),
(111, 12, 25, 'Branco');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_especificacoes_tecnicas`
--

CREATE TABLE `pew_especificacoes_tecnicas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `pew_especificacoes_tecnicas`
--

INSERT INTO `pew_especificacoes_tecnicas` (`id`, `titulo`, `data_controle`, `status`) VALUES
(12, 'Cor', '2018-04-15 05:23:05', 1),
(11, 'Material interno', '2018-03-29 02:32:55', 1),
(10, 'Material externo', '2018-03-29 02:32:38', 1),
(13, 'Tamanho', '2018-04-15 05:23:21', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_imagens_produtos`
--

CREATE TABLE `pew_imagens_produtos` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `posicao` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_imagens_produtos`
--

INSERT INTO `pew_imagens_produtos` (`id`, `id_produto`, `imagem`, `posicao`, `status`) VALUES
(24, 25, 'ducha-eletronica-hydra-fit-1b25.png', 1, 1),
(25, 2, '61146ba62330d2e50b5ca409e1e71e3a.jpg', 1, 1),
(26, 3, 'lampada-megaforth-espiral-vermelha.jpg', 1, 1),
(27, 4, 'caixa-de-correio-em-aluminio.jpg', 1, 1),
(28, 5, 'luminaria-de-mesa-12-leds.jpg', 1, 1),
(29, 7, 'kit-completo-de-varal-giratorio.jpg', 1, 1),
(30, 8, 'lampada-led-a60-ecp-9w-6500k.jpg', 1, 1),
(31, 10, 'torneira-eletrica-lumen-127-v.jpg', 1, 1),
(32, 11, 'torneira-eletrica-slim-4t-127v.jpg', 1, 1),
(33, 12, 'amplificador-aquario-hdtv.jpg', 1, 1),
(34, 13, 'antena-aquario-hdtv.jpg', 1, 1),
(35, 16, 'caixa-organizadora-furadeira-e-ferramentas.jpg', 1, 1),
(36, 17, 'chuveiro-ducha-eletronica-advanced.jpg', 1, 1),
(37, 18, 'ducha-hydra-127v-spot-8t.jpg', 1, 1),
(38, 19, 'ducha-optima-127v-hydra.jpg', 1, 1),
(39, 20, 'lampada-empalux-30w-super-forte.jpg', 1, 1),
(40, 21, 'luminaria-aletada-20w-ecp.jpg', 1, 1),
(41, 22, 'mouse-optico-sem-fio.jpg', 1, 1),
(42, 23, 'reparo-da-valvula-hydra-max.jpg', 1, 1),
(43, 24, 'resistencia-original-maxxi-ducha-lorenzetti.jpg', 1, 1),
(44, 25, '636776a65a991b062e473317037bfa2e.jpg', 1, 1),
(45, 26, 'tinta-latex-18-l-suvinil.jpg', 1, 1),
(46, 27, '3fb7d551850b8d9974a5c62fcea02a20.jpg', 1, 1),
(47, 28, 'varal-de-piso--2,20m-2-abas.jpg', 1, 1),
(48, 29, '0f66c54887d1ee9cb725832d05c56865.jpg', 1, 1),
(49, 30, 'a2939f34bf45af406e2e9a7051627e7d.jpg', 1, 1),
(50, 31, '6ed7f5e217ebc2f10b680bc6fbe628f7.jpg', 1, 1),
(51, 35, '1da332ae81a018fbab96ad70c6b05ab9.jpg', 1, 1),
(52, 36, '9c4e7e5a5e69931e6382719e60d05947.jpg', 1, 1),
(53, 36, '1824e85e59a337ec3182c95ad2f90c4a.jpg', 2, 1),
(54, 39, '376fe1fb709af0d9ea3e7fc034c09274.jpg', 1, 1),
(55, 40, 'ac0ca739a83d3976d7520ebdbcbc3bf3.jpg', 1, 1),
(56, 41, 'a0274d5040404159103dc975f5b9d9b2.jpg', 1, 1),
(57, 42, 'e96d5b7681094976fd2635bbce6bad1a.jpg', 1, 1),
(58, 43, '46b9d9723e1b3a9fb96535c1f05e094a.jpg', 1, 1),
(59, 43, 'fbf4c67faef72bb72c3c83b65c74f19a.jpg', 2, 1),
(60, 44, 'e859bc0784fd10db5c3f7440510b7409.jpg', 1, 1),
(61, 45, 'ac7a51dd15a56f5d16913fcb92ed5526.jpg', 1, 1),
(62, 47, 'c405edb1c1c3448fffd2e078f41349b2.jpg', 1, 1),
(63, 48, '790c82417df251db38b1f053f0695a46.jpg', 1, 1),
(64, 49, 'a4e96eefdff3fb87a01cb26422926f49.jpg', 1, 1),
(65, 50, '2c4cb4ba769c9a12bfb2c56e1f68d628.jpg', 1, 1),
(66, 51, 'bbef54e9fc37f48bcfc0632c00b0cdd5.jpg', 1, 1),
(67, 52, '0d181cc838fdb54171f73dd1922198e0.jpg', 1, 1),
(68, 53, 'eac20717f1e08bc75c2d45e32763b399.jpg', 1, 1),
(69, 54, 'a7f723320f43aa064a20243e1105253c.jpg', 1, 1),
(70, 55, '2c5eef979acd6037ba2bf0c169cc8d9d.jpg', 1, 1),
(71, 56, 'd85db7b20d4a48e35657dcc863e90da3.jpg', 1, 1),
(72, 58, '1c288b1f5ec16bee6a96181a53f03177.jpeg', 1, 1),
(73, 59, '28e74dbdc264057fc3d185105142dddd.jpg', 1, 1),
(74, 60, '497a0cbfe182b7634d11b6f76f9e43d3.jpg', 1, 1),
(75, 61, '001a18a88b463b2ef446ed1b9190f0c7.jpg', 1, 1),
(76, 62, '5ee1174ccba1c5e2710bed3659d7aa80.jpg', 1, 1),
(77, 63, 'bb780b8a743dd737795308d94ac61ec5.jpg', 1, 1),
(78, 64, 'b0365406a41bf95548daa86d6deb7803.jpg', 1, 1),
(79, 65, '792defac302898f9e04bacbae270ea49.jpg', 1, 1),
(80, 66, 'bfa3667d1236f54a071b04d3d1e0642d.jpg', 1, 1),
(81, 67, 'b2517b30a76dad334bbdb5aefbe70770.jpg', 1, 1),
(82, 68, '2b3b44dc5a20828b8a6d7d4b46b0d59d.jpg', 1, 1),
(83, 69, '892ec582217c7083a124cd8a32593937.jpg', 1, 1),
(84, 70, '0c7b35f4ac72af9fa07df379339b7dff.jpg', 1, 1),
(85, 71, '730a02e2927cc2a62bdf5d287bd258f9.jpeg', 1, 1),
(86, 72, '02b5b1213b2d57c6a34658d054e07665.jpg', 1, 1),
(87, 73, '7c0603c3a88691a4b8f34c0bcef4ebb1.jpg', 1, 1),
(88, 74, '85d236e0e7312e8a4874a6d698793825.jpg', 1, 1),
(89, 75, '2be9f42a7bd1bebc58198f501d3368f4.jpg', 1, 1),
(90, 76, '7b62aa209a6e24427d8150ad54b099fa.jpg', 1, 1),
(91, 77, '0f4dc0a5b6b0e0ce0a82139a84fdda88.jpg', 1, 1),
(92, 78, '80d46a1d9a20ac37b082136f5b660f19.jpg', 1, 1),
(93, 79, '4a1fe3f151ab7803adc0cfa7ad7f132f.jpg', 1, 1),
(94, 80, '7553a95117e12c97465fe4a78eca173b.jpg', 1, 1),
(95, 81, '476081a48dae3c13e0b3e38fbfb1762b.jpg', 1, 1),
(96, 82, '43d260e2b69a3fa45e66efce6c20a9a1.jpg', 1, 1),
(97, 83, 'af8a3a338d49b22426cbf410aac8df86.jpg', 1, 1),
(98, 84, '75d0ee219de612065a939cdd9e9aa1af.jpg', 1, 1),
(99, 85, '2891eb163660f193308be2897108b193.jpg', 1, 1),
(100, 86, 'e44f01666e4a19ade06b18133acd8064.jpg', 1, 1),
(101, 87, '7facd79e836c3488969660a5519d9c7f.jpg', 1, 1),
(102, 88, 'dc881e431133268d6ebe2134b8c368cb.jpg', 1, 1),
(103, 88, 'e5a96fb3430de488f63253d847b93405.jpg', 2, 1),
(104, 88, '234b98c10d72f8dc4a375b96ffa0bde1.jpg', 3, 1),
(105, 89, 'f7aa444f1db5f215d1e90ee4cce3e77f.jpg', 1, 1),
(106, 90, 'cafd68c2b68e000287a40f5128dacebd.jpg', 1, 1),
(107, 91, 'b2422de01703f9033250e3a7b7cf3544.jpg', 1, 1),
(108, 92, '5679eeedd4c965e07923ff59abbb5298.jpg', 1, 1),
(109, 93, 'fd4965767b1a8e70cf6ad1c6c22c6635.jpg', 1, 1),
(110, 94, '553fd4d87bf92bc34cce062177b8f4af.jpg', 1, 1),
(111, 95, 'ffd25e76bc42f1312e4c8a6ef49806a3.jpg', 1, 1),
(112, 95, '190c77c3b6984fc5372e170aed9d6d24.jpg', 2, 1),
(113, 96, '89483cf04ff06fd55c1170a2828ff8c2.jpg', 1, 1),
(114, 97, '3767e9d7d543d5fdde47201ef08e3ef7.jpg', 1, 1),
(115, 98, '5be777b527302244448d5ca8e6966665.jpg', 1, 1),
(116, 98, '082d6f35a8d8f851c8c9988cd7142ec8.jpg', 2, 1),
(117, 98, 'de51b78f2295ad1426d034e434f2cecd.jpg', 3, 1),
(118, 99, '84906ac15147c6875aa455980ffed29a.jpg', 1, 1),
(119, 99, 'aeda3ba5f0b233dd5058bff4bf0d232a.jpg', 2, 1),
(120, 100, 'ee6779a6d74927078a3303cc2d1505d7.jpg', 1, 1),
(121, 101, '6a5330c4cc154b29f4ebb295a0e2397b.jpg', 1, 1),
(122, 101, '422d8ef622a3aae57f04fcb4f298c7b3.jpg', 2, 1),
(123, 101, '666ab2592505bd489f06f31224ee4e66.jpg', 3, 1),
(124, 102, 'c3993be32e9ebd3e8da67e47545bfa88.jpg', 1, 1),
(125, 103, '24f8f04ee597289bb51baf708f5db397.jpg', 1, 1),
(126, 104, 'be26eb7d48871f3160f02561c8f50a39.jpg', 1, 1),
(127, 105, '89ae07232af94e913bbb637ddd6c077b.jpg', 1, 1),
(128, 106, '2ee9a919fe5e5d1ee805ce8eb8445689.jpg', 1, 1),
(129, 107, '9823362f42c18381c1a0434be396afa0.jpg', 1, 1),
(130, 108, 'b33e11d07c5d6480bc13ade99c327c2b.jpg', 1, 1),
(131, 109, 'bb0cd5224f2f501751f8a6d34d4baf5f.jpg', 1, 1),
(132, 110, 'f693cb92d981f5b54ba199fbf012a2c3.jpg', 1, 1),
(133, 111, '8a551e5f2dc218982d25fb5b00e9c927.jpg', 1, 1),
(134, 112, '8ccfd6b25e97e05d4c9ab226b54197cd.jpg', 1, 1),
(135, 113, '465db26ab497630cb19dd022c1ebacf9.jpg', 1, 1),
(136, 114, 'b56b9ab4ece8567cb9a62ad81a0234e5.jpg', 1, 1),
(137, 115, 'd1d5e842726e325e2bd05dba5a96f339.jpg', 1, 1),
(138, 116, '8e404a73651eaeca527d6a383f6232b9.jpg', 1, 1),
(139, 117, 'c56faf8d003d44a653a71472a1b310f2.jpg', 1, 1),
(140, 118, '0dd1719489a56d47b286b6d90624c652.jpg', 1, 1),
(141, 119, '59ca5d0acf40b660fb8cbdfbf69686a1.jpg', 1, 1),
(142, 120, '3596184bdd3593828cd8e52a2f5ce375.jpg', 1, 1),
(143, 121, '1c698e527a5ad7523625b9aece59cdb1.jpg', 1, 1),
(144, 122, '35e2e067719026b57718020949b9ce15.jpg', 1, 1),
(145, 123, 'ba944d91c1f0ec9e3930dcf73574d0bf.jpg', 1, 1),
(146, 124, 'd21e3dfaed796046be0e3a5d6970f735.jpg', 1, 1),
(147, 125, '19e214019ddef4c75b9d30d1ea351efc.jpg', 1, 1),
(148, 126, '9c1372a0432e27340eca77a3fad79624.jpg', 1, 1),
(149, 127, '93f7807dc9ab720afe4029fa4ff6c516.jpg', 1, 1),
(150, 128, '2bc4230e809e376e454d0b03cd5425b5.jpg', 1, 1),
(151, 129, '798d8be86eed1a143d0a0ff80a5fa0f5.jpg', 1, 1),
(152, 130, '0abbef53ff32edd18d34772820195d0a.jpg', 1, 1),
(153, 131, '92682cc9a18ab810189f769793bf51d1.jpg', 1, 1),
(154, 132, 'f5cd65de2a2b422ce272b0b0e2d1891d.jpg', 1, 1),
(155, 133, 'c78d0abaa0f5e12781dfe8961791e453.jpg', 1, 1),
(156, 134, 'f0f24cf3221de3ea1b176d7021fb6d6d.jpg', 1, 1),
(157, 135, 'c2430a5f96ddd622bd8f40fc1847fa06.jpg', 1, 1),
(158, 135, 'c6b12cbdac48d8fd3f3d2302dceee99d.jpg', 2, 1),
(159, 135, 'da58e5d0a74ff50e2664ee97bb8ea8ff.jpg', 3, 1),
(160, 135, 'e190cc05916015c864cc9e0a658c464a.jpg', 4, 1),
(161, 136, '94b1b5a5932f4b7870f52f09aacfd0b4.jpg', 1, 1),
(162, 137, '7f8a7677ed471416d846c1f9dbc40760.jpg', 1, 1),
(163, 138, '5dc33f49bc38025b202a048b24afcbb0.jpg', 1, 1),
(164, 139, '08aaca77ff62fb2baab9f99c029e0388.jpg', 1, 1),
(165, 139, '8a8bbe416e399ff62a5ca73776fa9d40.jpg', 2, 1),
(166, 140, '288d8e73a3f213a178c8a6db394abe0c.jpg', 1, 1),
(167, 141, 'e68df9205f4f7ec8bb0fc926bf5c9c37.jpg', 1, 1),
(168, 142, '90e60626aebff6fa2e8610a4285257af.jpg', 1, 1),
(169, 142, 'f4f650be46c86f881300cc9e5c28ab90.jpg', 2, 1),
(170, 142, '54c550802de17352b9366179980f4c22.jpg', 3, 1),
(171, 143, 'c02af23af686086705ad63e880b99ee2.jpg', 1, 1),
(172, 144, '332710c5b545e61b683d1cbc890c8444.jpg', 1, 1),
(173, 145, 'b4ee67565d2afa17fe332f64204ed22a.jpg', 1, 1),
(174, 145, '4872c222da4a6a7b2c852efbc6e18e60.jpg', 2, 1),
(175, 146, 'd7553b680ae59a6de03a5efc55468c53.jpg', 1, 1),
(176, 147, '6fb409a009c5c8c614e36fc4dbec0d59.jpg', 1, 1),
(177, 148, '89fd2c427900a1f364feeb749e25713c.jpg', 1, 1),
(178, 149, 'fcdcf6c8983d30c7ed9f2ba40d387bf5.jpg', 1, 1),
(179, 150, 'fc8176b981f8d79b28374c1b11905595.webp', 1, 1),
(180, 151, 'chuveiro-de-jardim-telescopio-com-tripe-513e.jpg', 1, 1),
(181, 152, 'kit-mangueira-expansivel-esguicho-e-adaptador-fbfb.jpg', 1, 1),
(182, 153, 'raquete-mata-mosquito-recarregavel-f3f9.jpg', 1, 1),
(183, 154, 'mata-mosquito-repelente-4ef2.jpg', 1, 1),
(184, 155, 'colher-de-frutas-tramontina-25b3.jpg', 1, 1),
(185, 156, 'conjunto-jardim-3-pecas-2248.jpg', 1, 1),
(186, 157, 'tesoura-de-poda-para-jardim-75ff.jpeg', 1, 1),
(187, 158, 'esguicho-pistola-jet-49b4.jpg', 1, 1),
(188, 159, 'relogio-temporizador--21a3.jpg', 1, 1),
(189, 160, 'pulverizador-compressao-previa-2aea.jpg', 1, 1),
(190, 161, 'aparador-de-grama-2bad.jpg', 1, 1),
(191, 162, 'produto-teste-checkout-fba2.png', 1, 1),
(192, 164, 'lampada-led-9w-acbf.jpg', 1, 1),
(193, 165, 'bule-de-cha-oriental-92dd.jpg', 1, 1),
(194, 166, 'cabide-para-lencos-e-cachecol-295b.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_links_menu`
--

CREATE TABLE `pew_links_menu` (
  `id` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_links_menu`
--

INSERT INTO `pew_links_menu` (`id`, `id_departamento`, `id_categoria`) VALUES
(129, 26, 20),
(130, 26, 40),
(131, 26, 25),
(132, 26, 32),
(133, 26, 17),
(134, 26, 31),
(135, 26, 18),
(136, 26, 35),
(137, 26, 19),
(138, 26, 27),
(139, 26, 36),
(140, 26, 39),
(141, 26, 26),
(142, 26, 34),
(143, 27, 21),
(144, 27, 22),
(145, 27, 23),
(146, 27, 24),
(147, 27, 33),
(148, 27, 30);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_marcas`
--

CREATE TABLE `pew_marcas` (
  `id` int(11) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_marcas`
--

INSERT INTO `pew_marcas` (`id`, `marca`, `descricao`, `ref`, `imagem`, `data_controle`, `status`) VALUES
(3, 'Hydra', '', 'hydra', 'marca-padrao.png', '2018-04-25 03:30:27', 1),
(139, 'Hydra', '', 'hydra', '', '2018-04-26 02:43:10', 1),
(140, 'Megaforth', '', 'megaforth', '', '2018-04-26 02:43:10', 1),
(141, 'Correios', '', 'correios', '', '2018-04-26 02:43:10', 1),
(142, 'Lumileds', '', 'lumileds', '', '2018-04-26 02:43:10', 1),
(144, 'ECP', '', 'ecp', '', '2018-04-26 02:43:10', 1),
(145, 'Aquario', '', 'aquario', '', '2018-04-26 02:43:10', 1),
(147, 'Lorezetti', '', 'lorezetti', '', '2018-04-26 02:43:10', 1),
(148, 'Empalux', '', 'empalux', '', '2018-04-26 02:43:10', 1),
(149, 'Integris', '', 'integris', '', '2018-04-26 02:43:10', 1),
(150, 'Lorenzetti', '', 'lorenzetti', '', '2018-04-26 02:43:10', 1),
(151, 'Dacar', '', 'dacar', '', '2018-04-26 02:43:10', 1),
(152, 'Suvinil', '', 'suvinil', '', '2018-04-26 02:43:10', 1),
(153, 'TV BOX', '', 'tv-box', '', '2018-04-26 02:43:10', 1),
(154, 'Art&Bel', '', 'art&bel', '', '2018-04-26 02:43:10', 1),
(155, 'Interponte', '', 'interponte', '', '2018-04-26 02:43:10', 1),
(156, 'Casita', '', 'casita', '', '2018-04-26 02:43:10', 1),
(157, 'Gedex', '', 'gedex', '', '2018-04-26 02:43:10', 1),
(158, 'LOL', '', 'lol', '', '2018-04-26 02:43:10', 1),
(159, 'Top House', '', 'top-house', '', '2018-04-26 02:43:10', 1),
(160, 'Rocie', '', 'rocie', '', '2018-04-26 02:43:10', 1),
(161, 'Haoyun', '', 'haoyun', '', '2018-04-26 02:43:10', 1),
(162, 'Clink', '', 'clink', '', '2018-04-26 02:43:10', 1),
(163, 'Junzilan CO.', '', 'junzilan-co.', '', '2018-04-26 02:43:10', 1),
(164, 'Lig brin', '', 'lig-brin', '', '2018-04-26 02:43:10', 1),
(165, 'Unahome', '', 'unahome', '', '2018-04-26 02:43:10', 1),
(166, 'Wincy', '', 'wincy', '', '2018-04-26 02:43:10', 1),
(167, 'Keita', '', 'keita', '', '2018-04-26 02:43:10', 1),
(168, 'PlasÃºtil', '', 'plasutil', '', '2018-04-26 02:43:10', 1),
(169, 'TEKBOND', '', 'tekbond', '', '2018-04-26 02:43:10', 1),
(170, 'Kala', '', 'kala', '', '2018-04-26 02:43:10', 1),
(171, 'zarifer', '', 'zarifer', '', '2018-04-26 02:43:10', 1),
(172, 'ZUMPLAST', '', 'zumplast', '', '2018-04-26 02:43:10', 1),
(173, 'FLORINI', '', 'florini', '', '2018-04-26 02:43:10', 1),
(174, 'WORKER', '', 'worker', '', '2018-04-26 02:43:10', 1),
(175, 'ATLAS', '', 'atlas', '', '2018-04-26 02:43:10', 1),
(176, 'Unika', '', 'unika', '', '2018-04-26 02:43:10', 1),
(177, 'elastil', '', 'elastil', '', '2018-04-26 02:43:10', 1),
(178, 'exbom', '', 'exbom', '', '2018-04-26 02:43:10', 1),
(179, 'ADF', '', 'adf', '', '2018-04-26 02:43:10', 1),
(180, 'OTT', '', 'ott', '', '2018-04-26 02:43:10', 1),
(181, 'BT', '', 'bt', '', '2018-04-26 02:43:10', 1),
(182, 'Pop socket', '', 'pop-socket', '', '2018-04-26 02:43:10', 1),
(183, 'M.N.Y', '', 'm.n.y', '', '2018-04-26 02:43:10', 1),
(184, 'VR CAM ', '', 'vr-cam-', '', '2018-04-26 02:43:10', 1),
(185, 'Unihome', '', 'unihome', '', '2018-04-26 02:43:10', 1),
(186, 'Infokit', '', 'infokit', '', '2018-04-26 02:43:10', 1),
(187, 'onvif', '', 'onvif', '', '2018-04-26 02:43:10', 1),
(188, 'Mxmidia', '', 'mxmidia', '', '2018-04-26 02:43:10', 1),
(189, 'Arthouse', '', 'arthouse', '', '2018-04-26 02:43:10', 1),
(190, 'Genius', '', 'genius', '', '2018-04-26 02:43:10', 1),
(191, 'KE home ', '', 'ke-home-', '', '2018-04-26 02:43:10', 1),
(192, 'alianÃ§a', '', 'alianca', '', '2018-04-26 02:43:10', 1),
(193, 'Soprano', '', 'soprano', '', '2018-04-26 02:43:10', 1),
(194, 'Western', '', 'western', '', '2018-04-26 02:43:10', 1),
(195, 'STAM', '', 'stam', '', '2018-04-26 02:43:10', 1),
(196, 'Vonder', '', 'vonder', '', '2018-04-26 02:43:10', 1),
(197, 'Skysuper', '', 'skysuper', '', '2018-04-26 02:43:10', 1),
(198, 'Diversos', '', 'diversos', '', '2018-04-26 02:43:10', 1),
(199, 'Reliza', '', 'reliza', '', '2018-04-26 02:43:10', 1),
(200, 'Plastleo ', '', 'plastleo-', '', '2018-04-26 02:43:10', 1),
(201, 'Wellmix', '', 'wellmix', '', '2018-04-26 02:43:10', 1),
(202, 'Bestfer', '', 'bestfer', '', '2018-04-26 02:43:10', 1),
(203, 'Novo Seculo', '', 'novo-seculo', '', '2018-04-26 02:43:10', 1),
(204, 'Azpr', '', 'azpr', '', '2018-04-26 02:43:10', 1),
(205, 'Rei do Osso', '', 'rei-do-osso', '', '2018-04-26 02:43:10', 1),
(206, 'Sanol dog', '', 'sanol-dog', '', '2018-04-26 02:43:10', 1),
(207, 'Coza Pets', '', 'coza-pets', '', '2018-04-26 02:43:10', 1),
(208, 'xxxx', '', 'xxxx', '', '2018-04-26 02:43:10', 1),
(209, 'Uatt Casa DecoraÃ§ao ', '', 'uatt-casa-decoracao-', '', '2018-04-26 02:43:10', 1),
(210, 'Compeg', '', 'compeg', '', '2018-04-26 02:43:10', 1),
(211, 'Redstar sport', '', 'redstar-sport', '', '2018-04-26 02:43:10', 1),
(212, 'Scotch', '', 'scotch', '', '2018-04-26 02:43:10', 1),
(213, 'Eterny', '', 'eterny', '', '2018-04-26 02:43:10', 1),
(214, 'Baoji', '', 'baoji', '', '2018-04-26 02:43:10', 1),
(215, 'Bem fixa', '', 'bem-fixa', '', '2018-04-26 02:43:10', 1),
(216, 'BMAX', '', 'bmax', '', '2018-04-26 02:43:10', 1),
(217, 'Feimoshi', '', 'feimoshi', '', '2018-04-26 02:43:10', 1),
(218, 'PUMP', '', 'pump', '', '2018-04-26 02:43:10', 1),
(219, '123 Util', '', '123-util', '', '2018-04-26 02:43:10', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_minha_conta`
--

CREATE TABLE `pew_minha_conta` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` varchar(255) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_minha_conta`
--

INSERT INTO `pew_minha_conta` (`id`, `usuario`, `email`, `senha`, `celular`, `telefone`, `cpf`, `data_nascimento`, `sexo`, `data_cadastro`, `data_controle`, `status`) VALUES
(68, 'Rogerio Mendes', 'reyrogerioweb@gmail.com', '08541bb36f049db6004fd98457138485', '(41) 99753-6262', '', '05453531908', '1998-07-29', 'masculino', '2018-06-05 04:12:05', '2018-06-05 04:12:05', 1),
(69, 'Isabel Alves Pereira', 'isabelalvespereira@yahoo.com.br', '1b9e6d04f8c3cf0ce79482e3963ee00d', '(41) 3018-2477', '', '72991534915', '1968-07-10', 'feminino', '2018-06-28 04:11:36', '2018-06-28 04:11:36', 0),
(70, 'Yuri dal Santo de Mello', 'yuridemeello@gmail.com', '5abd82117b1f85060467aac2ecd292ad', '(41) 99555-5098', '', '09484859909', '2000-05-01', 'masculino', '2018-06-28 05:27:18', '2018-06-28 05:27:18', 0),
(71, 'Juan Desenvolvedor', 'juanweb@efectusdigital.com.br', 'fa61db9a31f047795b62b65ac357cb14', '(41) 99191-2980', '(41) 99191-2980', '09163977931', '1999-10-10', 'masculino', '2018-07-24 12:04:12', '2018-07-24 12:04:12', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_newsletter`
--

CREATE TABLE `pew_newsletter` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_newsletter`
--

INSERT INTO `pew_newsletter` (`id`, `id_franquia`, `nome`, `email`, `data`) VALUES
(4, 11, 'Rogerio Mendes', 'rogeiro@efectusweb.com.br', '2018-07-24 11:36:16'),
(5, 11, 'Yuri de Mello', 'yuridemeello@gmail.com', '2018-07-24 11:55:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_orcamentos`
--

CREATE TABLE `pew_orcamentos` (
  `id` int(11) NOT NULL,
  `nome_cliente` varchar(255) NOT NULL,
  `telefone_cliente` varchar(255) NOT NULL,
  `email_cliente` varchar(255) NOT NULL,
  `cpf_cliente` varchar(255) NOT NULL,
  `msg_cliente` text NOT NULL,
  `token_carrinho` varchar(255) NOT NULL,
  `porcentagem_desconto` varchar(255) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `data_pedido` datetime NOT NULL,
  `data_vencimento` date NOT NULL,
  `data_controle` datetime NOT NULL,
  `modify_controle` varchar(255) NOT NULL,
  `status_orcamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_orcamentos`
--

INSERT INTO `pew_orcamentos` (`id`, `nome_cliente`, `telefone_cliente`, `email_cliente`, `cpf_cliente`, `msg_cliente`, `token_carrinho`, `porcentagem_desconto`, `id_vendedor`, `data_pedido`, `data_vencimento`, `data_controle`, `modify_controle`, `status_orcamento`) VALUES
(160, 'Rogerio Mendes', '(41) 99753-6262', 'reyrogerioweb@gmail.com', '05453531908', '', 'CTK2a8a68cd21', '10', 1, '2018-06-21 11:34:10', '2018-07-21', '2018-06-21 11:34:10', '1', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_pedidos`
--

CREATE TABLE `pew_pedidos` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `codigo_confirmacao` varchar(255) NOT NULL,
  `codigo_transacao` varchar(255) NOT NULL,
  `codigo_transporte` varchar(255) NOT NULL,
  `vlr_frete` varchar(255) NOT NULL,
  `codigo_pagamento` tinyint(4) NOT NULL,
  `codigo_rastreamento` varchar(255) NOT NULL,
  `payment_link` varchar(255) NOT NULL,
  `referencia` varchar(255) NOT NULL,
  `token_carrinho` varchar(255) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` varchar(255) NOT NULL,
  `cpf_cliente` varchar(14) NOT NULL,
  `email_cliente` varchar(255) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status_transporte` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_produtos`
--

CREATE TABLE `pew_produtos` (
  `id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `codigo_barras` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` varchar(255) NOT NULL,
  `preco_custo` varchar(255) NOT NULL,
  `preco_promocao` varchar(255) NOT NULL,
  `preco_sugerido` varchar(255) NOT NULL,
  `promocao_ativa` int(11) NOT NULL,
  `desconto_relacionado` decimal(10,0) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `id_cor` int(11) NOT NULL,
  `estoque` int(11) NOT NULL,
  `estoque_baixo` int(11) NOT NULL,
  `tempo_fabricacao` int(11) NOT NULL,
  `descricao_curta` varchar(255) NOT NULL,
  `descricao_longa` text NOT NULL,
  `url_video` varchar(255) NOT NULL,
  `peso` varchar(255) NOT NULL,
  `comprimento` varchar(255) NOT NULL,
  `largura` varchar(255) NOT NULL,
  `altura` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
  `visualizacoes` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_produtos`
--

INSERT INTO `pew_produtos` (`id`, `sku`, `codigo_barras`, `nome`, `preco`, `preco_custo`, `preco_promocao`, `preco_sugerido`, `promocao_ativa`, `desconto_relacionado`, `marca`, `id_cor`, `estoque`, `estoque_baixo`, `tempo_fabricacao`, `descricao_curta`, `descricao_longa`, `url_video`, `peso`, `comprimento`, `largura`, `altura`, `data`, `visualizacoes`, `status`) VALUES
(2, '7896650402263', '', 'Ducha EletrÃ´nica Hydra Fit', '179.95', '', '149.95', '', 1, '0', 'Hydra', 0, 3, 5, 0, 'Economia de atÃ© 91% de energia\r\nJato intenso\r\nFÃ¡cil instalaÃ§Ã£o e fÃ¡cil manutenÃ§Ã£o\r\nSistema eletrÃ´nico de temperatura\r\nFÃ¡cil limpeza do instalador', 'Economia de atÃ© 91% de energia\r\nJato intenso\r\nFÃ¡cil instalaÃ§Ã£o e fÃ¡cil manutenÃ§Ã£o\r\nSistema eletrÃ´nico de temperatura\r\nFÃ¡cil limpeza do instalador', '', '0.800', '36', '15', '18', '2018-04-26 02:30:04', 0, 1),
(3, '0', '', 'LÃ¢mpada Megaforth Espiral Vermelha', '14.99', '', '0', '', 0, '0', 'Megaforth', 0, 20, 5, 0, '', '', '', '0.300', '5', '5', '8', '2018-04-26 02:30:04', 0, 0),
(4, '0', '', 'Caixa de Correio em AlumÃ­nio', '84.99', '', '0', '', 0, '0', 'Correios', 0, 10, 5, 0, '', '', '', '3', '30', '20', '40', '2018-04-26 02:30:04', 0, 1),
(5, '7866889905750', '', 'LuminÃ¡ria de Mesa 12 LEDs', '69.75', '', '', '', 0, '0', 'Lumileds', 0, 10, 5, 0, '', '', '', '0.500', '10', '10', '40', '2018-04-26 02:30:04', 0, 1),
(7, '0', '', 'Kit Completo de Varal GiratÃ³rio', '149.95', '', '', '', 0, '0', ' ', 0, 1, 5, 0, 'Varal giratÃ³rio com 9 cordas\r\nAcompanha suporte para o encaixe do varal (QuadripÃ©)', 'Varal giratÃ³rio com 9 cordas\r\nAcompanha suporte para o encaixe do varal (QuadripÃ©)', '', '3', '200', '200', '200', '2018-04-26 02:30:04', 0, 0),
(8, '7893071574890', '', 'LÃ¢mpada LED A60 ECP 9W 6500K', '21.99', '', '14.40', '', 1, '0', 'ECP', 0, 20, 5, 0, '', '', '', '0.075', '6', '6', '11.5', '2018-04-26 02:30:04', 0, 0),
(10, '0', '', 'Torneira ElÃ©trica Lumen 127 V', '160.00', '', '', '', 0, '0', 'Hydra', 0, 5, 5, 0, '', '', '', '1', '30', '15', '25', '2018-04-26 02:30:04', 0, 1),
(11, '7896650402171', '', 'Torneira ElÃ©trica Slim 4T 127v', '179.75', '', '', '', 0, '0', 'Hydra', 0, 5, 5, 0, 'Maior conforto e praticidade\r\nEconomia de atÃ© 58% energia\r\nSistema multitemperatura\r\nAmpla rede de serviÃ§os autorizados', 'Maior conforto e praticidade\r\nEconomia de atÃ© 58% energia\r\nSistema multitemperatura\r\nAmpla rede de serviÃ§os autorizados', '', '2', '32', '8', '32', '2018-04-26 02:30:04', 0, 1),
(12, '7898127600776', '', 'Amplificador de sinal AquÃ¡rio HDTV', '129.75', '0.00', '0.00', '129.75', 0, '0', 'Aquario', 0, 1, 5, 1, 'Amplifica os sinais de TV para VHF, UHF E HDTV\r\nIdeal para locais onde o alcance do sinal de TV nÃ£o chega com boa qualidade\r\nCompatÃ­vel com qualquer antena e televisor disponÃ­vel no mercado\r\n2 saÃ­das para TV\r\nFonte integrada Bivolt\r\n', '<p>Amplifica os sinais de TV para VHF, UHF E HDTV Ideal para locais onde o alcance do sinal de TV n&atilde;o chega com boa qualidade Compat&iacute;vel com qualquer antena e televisor dispon&iacute;vel no mercado 2 sa&iacute;das para TV Fonte integrada Bivolt</p>', '', '0.7', '6', '15', '10', '2018-07-25 02:34:06', 0, 1),
(13, '7898127602237', '', 'Antena AquÃ¡rio HDTV', '79.95', '', '', '', 0, '0', 'Aquï¿½rio', 0, 10, 5, 0, '', '', '', '0.285', '7', '13', '7', '2018-04-26 02:30:04', 0, 0),
(16, '7898071460280', '', 'Caixa Organizadora Furadeira e Ferramentas', '43.99', '', '', '', 0, '0', ' ', 0, 5, 5, 0, '', '', '', '1', '30', '20', '30', '2018-04-26 02:30:04', 0, 0),
(17, '0', '', 'Chuveiro Ducha EletrÃ´nica Advanced', '95.00', '', '', '', 0, '0', 'Lorezetti', 0, 5, 5, 0, '', '', '', '1.1', '50', '15', '23', '2018-04-26 02:30:04', 0, 0),
(18, '7896650401211', '', 'Ducha Hydra 127V Spot 8T', '98.75', '', '', '', 0, '0', 'Hydra', 0, 5, 5, 0, '', '', '', '0.600', '25', '20', '35', '2018-04-26 02:30:04', 0, 0),
(19, '7896650402027', '', 'Ducha Optima 127V Hydra', '189.90', '', '', '', 0, '0', 'Hydra', 0, 5, 5, 0, '', '', '', '0.560', '34', '30', '17', '2018-04-26 02:30:04', 0, 0),
(20, '0', '', 'LÃ¢mpada Empalux 30W Super Forte', '30.00', '', '26.30', '', 1, '0', 'Empalux', 0, 5, 5, 0, '', '', '', '0.300', '5', '10', '6', '2018-04-26 02:30:04', 0, 0),
(21, '7893071575781', '', 'LuminÃ¡ria Aletada 20w ECP', '64.99', '', '', '', 0, '0', 'ECP', 0, 5, 5, 0, '', '', '', '1.3', '75', '10', '6', '2018-04-26 02:30:04', 0, 0),
(22, '7890807188703', '', 'Mouse Ã“ptico sem Fio', '49.95', '', '', '', 0, '0', 'Integris', 0, 10, 5, 0, '', '', '', '0.300', '7', '5', '3', '2018-04-26 02:30:04', 0, 0),
(23, '7898128470583', '', 'Reparo da VÃ¡lvula Hydra Max', '58.99', '', '', '', 0, '0', 'Hydra', 0, 10, 5, 0, '', '', '', '0.850', '8', '8', '10', '2018-04-26 02:30:04', 0, 0),
(24, '7896451824783', '', 'ResistÃªncia Original Maxi Ducha Lorenzetti', '19.75', '', '', '', 0, '0', 'Lorenzetti', 0, 10, 5, 0, 'CompatÃ­vel com os modelos Lorenzetti Maxi Ducha , Maxi Banho e Big Banho,  Torneira VersÃ¡til, Bella Ducha Relax e Jet Set 3 Temperaturas, Bello Banho. ', 'CompatÃ­vel com os modelos Lorenzetti Maxi Ducha , Maxi Banho e Big Banho,  Torneira VersÃ¡til, Bella Ducha Relax e Jet Set 3 Temperaturas, Bello Banho. ', '', '0.400', '3', '5', '10', '2018-04-26 02:30:04', 0, 1),
(25, '7897560701965', '', 'Tinta  AcrÃ­lico Premium fosco Dacar  18L', '219.95', '', '', '', 0, '0', 'Dacar', 0, 1, 5, 0, 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nÃ’tima ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 18 litros: 300 m2  a   350 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2', 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nÃ’tima ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 18 litros: 300 m2  a   350 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2', '', '18.60', '30', '30', '40', '2018-04-26 02:30:04', 0, 1),
(26, '0', '', 'Tinta Latex 18 L Suvinil', '280.30', '', '', '', 0, '0', 'Suvinil', 0, 5, 5, 0, '', '', '', '18.60', '25', '25', '35', '2018-04-26 02:30:04', 0, 0),
(27, '7897560701958', '', 'Tinta  Acrilica fosco Premium Dacar 3,6L ', '54.95', '', '', '', 0, '0', 'Dacar', 0, 1, 5, 0, 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nÃ’tima ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 3,6 litros (3,6 L): 60 m2  a   76 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2', 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nÃ’tima ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 3,6 litros (3,6 L): 60 m2  a   76 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2', '', '5', '1', '17', '19', '2018-04-26 02:30:04', 0, 1),
(28, '0', '', 'Varal de Piso  2,20m 2 abas', '70.00', '', '', '', 0, '0', ' ', 0, 1, 5, 0, '', '', '', '3', '1.60', '2.20', '1.30', '2018-04-26 02:30:04', 0, 0),
(29, '7897560705642', '', 'Tinta AcrÃ­lica Fosco Profissional 3,6 Litros DACAR', '32.99', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'Interiores\r\nQualidade e Economia\r\nBoa ResistÃªncia e Cobertura\r\nBom Rendimento\r\nÃ“timo Acabamento\r\nMenos Respingos e Menos sujeira\r\nFÃ¡cil AplicaÃ§Ã£o\r\nSecagem RÃ¡pida\r\nLata 3,6 litros: 30 m2  a 50 m2 por demÃ£o\r\nAplicaÃ§Ã£o:\r\nUtilizar rolo, trincha ou pi', 'Interiores\r\nQualidade e Economia\r\nBoa ResistÃªncia e Cobertura\r\nBom Rendimento\r\nÃ“timo Acabamento\r\nMenos Respingos e Menos sujeira\r\nFÃ¡cil AplicaÃ§Ã£o\r\nSecagem RÃ¡pida\r\nLata 3,6 litros: 30 m2  a 50 m2 por demÃ£o\r\nAplicaÃ§Ã£o:\r\nUtilizar rolo, trincha ou pi', '', '4.77', '1', '16.80', '18.9', '2018-04-26 02:30:04', 0, 1),
(30, '7897560705659', '', 'Tinta Acrilica Fosco Profissional 18 litros Dacar', '109.95', '', '', '', 0, '0', 'Dacar', 0, 1, 5, 0, 'Interiores\r\nQualidade e Economia\r\nBoa ResistÃªncia e Cobertura\r\nBom Rendimento\r\nÃ“timo Acabamento\r\nMenos Respingos e Menos sujeira\r\nFÃ¡cil AplicaÃ§Ã£o\r\nSecagem RÃ¡pida\r\nLata 18 litros: 150 m2 a 250 m2 por demÃ£o\r\nAplicaÃ§Ã£o:\r\nUtilizar rolo, trincha ou pi', 'Interiores\r\nQualidade e Economia\r\nBoa ResistÃªncia e Cobertura\r\nBom Rendimento\r\nÃ“timo Acabamento\r\nMenos Respingos e Menos sujeira\r\nFÃ¡cil AplicaÃ§Ã£o\r\nSecagem RÃ¡pida\r\nLata 18 litros: 150 m2 a 250 m2 por demÃ£o\r\nAplicaÃ§Ã£o:\r\nUtilizar rolo, trincha ou pi', '', '19.54', '1', '23.5', '34.8', '2018-04-26 02:30:04', 0, 1),
(31, '7897560702511', '', 'Acrilico Standard 3,6 litros Dacar', '38.99', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nBoa ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 3,6 litros (3,6 L): 40 m2  a   60 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2 de', 'Exteriores e Interiores\r\nExcelente Rendimento\r\nBaixo Odor\r\nBoa Cobertura\r\nFino Acabamento\r\nBoa ResistÃªncia a Sol e Chuva\r\nRendimento GalÃ£o 3,6 litros (3,6 L): 40 m2  a   60 m2 por demÃ£o\r\nAplicaÃ§Ã£o: Utilizar rolo, trincha ou pistola.\r\nAplicar 1 a 2 de', '', '5', '1', '17.00', '19.00', '2018-04-26 02:30:04', 0, 1),
(35, '7897560702498', '', 'Selador Acrilico pigmentado premium 3,6 litros Dacar', '23.99', '', '', '', 0, '0', 'Dacar', 0, 1, 5, 0, 'Alto poder de penetraÃ§Ã£o \r\nÃ“tima aderencia\r\nSela e uniformiza a absorÃ§Ã£o de Tintas\r\nFixa partÃ­culas Soltas\r\nExcelente resistÃªncia\r\nSecagem rÃ¡pida', 'Alto poder de penetraÃ§Ã£o \r\nÃ“tima aderencia\r\nSela e uniformiza a absorÃ§Ã£o de Tintas\r\nFixa partÃ­culas Soltas\r\nExcelente resistÃªncia\r\nSecagem rÃ¡pida', '', '5', '1', '17.00', '19.00', '2018-04-26 02:30:04', 0, 1),
(36, '7202160', '', 'Tv  Box 4K Ultra HD H.265', '249.95', '', '199.95', '', 1, '0', 'TV BOX', 0, 1, 5, 0, 'Com esse aparelho vocÃª navega na internet atravÃªs da sua televisÃ£o, com o sistema operacional ANDROID vocÃª ganha acesso aos inÃºmeros aplicativos, como os das redes sociais Facebook, Twitter, Instagram, Skype, Youtube, WhatsApp e aplicativos de jogos.', 'Com esse aparelho vocÃª navega na internet atravÃªs da sua televisÃ£o, com o sistema operacional ANDROID vocÃª ganha acesso aos inÃºmeros aplicativos, como os das redes sociais Facebook, Twitter, Instagram, Skype, Youtube, WhatsApp e aplicativos de jogos.', '', '0.5', '21', '15', '6', '2018-04-26 02:30:04', 0, 0),
(39, '7898500818415', '', 'Conjunto com 2 taÃ§as de vidro para cerveja - Mestre cervejeiro', '1', '', '', '', 0, '0', 'Art&Bel', 0, 1, 5, 0, 'Conjunto com 2 taÃ§as de cerveja - Rotulo cerveja Pilsen', 'Conjunto com 2 taÃ§as de cerveja - Rotulo cerveja Pilsen', '', '0.491', '8', '20', '24', '2018-04-26 02:30:04', 0, 0),
(40, '8655210798036', '', 'Jogo de 4 canecas esmaltadas 370 ml', '1', '', '', '', 0, '0', 'Interponte', 0, 1, 5, 0, '4 canecas esmaltadas de 370 ml (vermelho/verde/creme/branco)', '4 canecas esmaltadas de 370 ml (vermelho/verde/creme/branco)', '', '0.35', '8', '18', '18', '2018-04-26 02:30:04', 0, 0),
(41, '7899790904956', '', 'Cafeteira tipo italiana - 6 xicaras/300ml', '1', '', '', '', 0, '0', 'Casita', 0, 1, 5, 0, 'Cafeteira tipo italiana , faz 300 ml de cafe, produto de alumÃ­nio', 'Cafeteira tipo italiana , faz 300 ml de cafe, produto de alumÃ­nio', '', '0.308', '20', '15', '20', '2018-04-26 02:30:04', 0, 0),
(42, '7897185930832', '', 'Luva de silicone- ECO lumi verde', '1', '', '', '', 0, '0', 'Gedex', 0, 1, 5, 0, '100% silicone,resistente a 220 graus, antiderrapante,lavÃ¡vel', '100% silicone,resistente a 220 graus, antiderrapante,lavÃ¡vel', '', '0.108', '33', '14', '33', '2018-04-26 02:30:04', 0, 0),
(43, '7899450725396', '', 'Kit Avental e Luva - Delicious', '79.95', '', '', '', 0, '0', 'LOL', 0, 1, 5, 0, 'Avental de corpo inteiro e feito 100% algodÃ£o com bolso para pano de prato. Luva com parte funcional em silicone indispensÃ¡vel para preparaÃ§Ã£o de alimentos em forno. Luva de tecido em silicone Ã© resistente atÃ© 220 graus centÃ­grados. ', 'Avental de corpo inteiro e feito 100% algodÃ£o com bolso para pano de prato. Luva com parte funcional em silicone indispensÃ¡vel para preparaÃ§Ã£o de alimentos em forno. Luva de tecido em silicone Ã© resistente atÃ© 220 graus centÃ­grados. ', '', '0.483', '32', '27', '32', '2018-04-26 02:30:04', 0, 1),
(44, '7898513872367', '', 'Cortador de vegetais para macarrÃ£o de legumes', '29.95', '', '', '', 0, '0', 'Top House', 0, 1, 5, 0, 'FÃ¡cil e prÃ¡tico, basta posicionar o vegetal em sua extremidade e girar, perfeito para preparo de spaguetti de verduras,para servir saladas de forma prÃ¡tica e elegante.Tampa com fixador para alimentos', 'FÃ¡cil e prÃ¡tico, basta posicionar o vegetal em sua extremidade e girar, perfeito para preparo de spaguetti de verduras,para servir saladas de forma prÃ¡tica e elegante.Tampa com fixador para alimentos', '', '0.145', '14', '8', '14', '2018-04-26 02:30:04', 0, 1),
(45, '7899755633303', '', 'Kit sushi', '1', '', '', '', 0, '0', 'Rocie', 0, 1, 5, 0, 'Conjunto contendo 4 hashis, 2 porta hashis e 2 molheiras', 'Conjunto contendo 4 hashis, 2 porta hashis e 2 molheiras', '', '0.411', '32', '17', '32', '2018-04-26 02:30:04', 0, 0),
(47, '7890001021745', '', 'Rack para pratos de aÃ§o inoxidÃ¡vel', '1', '', '', '', 0, '0', 'Haoyun', 0, 1, 5, 0, 'Para pratos, talheres e copos', 'Para pratos, talheres e copos', '', '2.06', '49', '16', '49', '2018-04-26 02:30:04', 0, 0),
(48, '7899850303460', '', 'BalanÃ§a Digital de Cozinha 10kg', '59.90', '', '29.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'BalanÃ§a moderna com capacidade para 10kg\r\nMÃ¡xima precisÃ£o na mediÃ§Ã£o de pequenas quantidades\r\n2 pilhas AA incluÃ­das', 'BalanÃ§a moderna com capacidade para 10kg\r\nMÃ¡xima precisÃ£o na mediÃ§Ã£o de pequenas quantidades\r\n2 pilhas AA incluÃ­das', '', '0.42', '25', '18', '25', '2018-04-26 02:30:04', 0, 1),
(49, '6951740389822', '', 'Prensa para hambÃºrguer', '1', '', '', '', 0, '0', 'Junzilan CO.', 0, 1, 5, 0, 'Prensa de alumÃ­nio para hamburguer', 'Prensa de alumÃ­nio para hamburguer', '', '0.27', '12', '12', '12', '2018-04-26 02:30:04', 0, 0),
(50, '7899450725334', '', 'Fatiador de batata palito', '1', '', '', '', 0, '0', 'Lol', 0, 1, 5, 0, 'Fatiador de batata palito', 'Fatiador de batata palito', '', '0.24', '17', '9', '17', '2018-04-26 02:30:04', 0, 0),
(51, '7898282400808', '', 'Fatiador e desfiador multiuso', '1', '', '', '', 0, '0', 'Lig brin', 0, 1, 5, 0, 'LÃ¢mina para fatiar\r\nPerfurado para desfiar', 'LÃ¢mina para fatiar\r\nPerfurado para desfiar', '', '0.172', '28', '11', '28', '2018-04-26 02:30:04', 0, 0),
(52, '7899386517768', '', 'Forma de silicone', '1', '', '', '', 0, '0', 'Casita', 0, 1, 5, 0, 'Multiuso pra abolo,gelatina,mousse e pudim', 'Multiuso pra abolo,gelatina,mousse e pudim', '', '0.05', '26', '26', '26', '2018-04-26 02:30:04', 0, 0),
(53, '7899670814702', '', 'Formas de silicone', '1', '', '', '', 0, '0', 'Unahome', 0, 1, 5, 0, 'Formas de silicone, vocÃª deixa sua cozinha mais organizada, higiÃªnica,prÃ¡tica e segura.', 'Formas de silicone, vocÃª deixa sua cozinha mais organizada, higiÃªnica,prÃ¡tica e segura.', '', '0.17', '30', '19', '30', '2018-04-26 02:30:04', 0, 0),
(54, '7898549344791', '', 'Mini Mixer Wincy', '1', '', '', '', 0, '0', 'Wincy', 0, 1, 5, 0, 'Prepara chocolate,leite,coquetel,iogurte.\r\nFunciona apenas com duas pilhas comuns do tipo AA\r\n', 'Prepara chocolate,leite,coquetel,iogurte.\r\nFunciona apenas com duas pilhas comuns do tipo AA\r\n', '', '0.1', '27', '8', '27', '2018-04-26 02:30:04', 0, 0),
(55, '7897839700910', '', 'Fura Coco', '16.99', '', '', '', 0, '0', 'Keita', 0, 1, 5, 0, 'Seguro, fÃ¡cil e higiÃªnico', 'Seguro, fÃ¡cil e higiÃªnico', '', '0.07', '10', '3', '18', '2018-04-26 02:30:04', 0, 1),
(56, '7896042044927', '', 'Porta condimentos ', '34.95', '', '', '', 0, '0', 'PlasÃºtil', 0, 1, 5, 0, '7 peÃ§as, contÃ©m 1 base e 6 porta condimentos\r\n100ml cada\r\nPara pia ou paredes\r\nOrganiza os condimentos e otimiza espaÃ§o', '7 peÃ§as, contÃ©m 1 base e 6 porta condimentos\r\n100ml cada\r\nPara pia ou paredes\r\nOrganiza os condimentos e otimiza espaÃ§o', '', '0.18', '37', '8', '37', '2018-04-26 02:30:04', 0, 1),
(58, '7898904869051', '', 'Adesivo anaerÃ³bico Trava Rosca 120 Alta ResistÃªncia 10g TekBond', '1', '', '', '', 0, '0', 'TEKBOND', 0, 1, 5, 0, 'AplicaÃ§Ã£o:\r\nÃ‰ recomendado principalmente para travamento de roscas, parafusos, porcas e prisioneiros de mÃ©dio e grande porte que necessitam de alta resistÃªncia Ã  desmontagem.\r\n \r\nCaracterÃ­sticas:\r\nÃ‰ um adesivo anaerÃ³bico monocomponente, uma vez a', 'AplicaÃ§Ã£o:\r\nÃ‰ recomendado principalmente para travamento de roscas, parafusos, porcas e prisioneiros de mÃ©dio e grande porte que necessitam de alta resistÃªncia Ã  desmontagem.\r\n \r\nCaracterÃ­sticas:\r\nÃ‰ um adesivo anaerÃ³bico monocomponente, uma vez a', '', '0.1', '7.5', '3', '16', '2018-04-26 02:30:04', 0, 0),
(59, '7899737501927', '', 'Moedor de Carne Eletrico', '459.95', '', '398.75', '', 1, '0', 'Kala', 0, 1, 5, 0, '- Moedor elÃ©trico de carne \r\n- TensÃ£o: 220v \r\n- PotÃªncia: 500w \r\n- Com bandeja e pilÃ£o \r\n- 3 tipos de disco: fino, mÃ©dio e grosso \r\n- Corpo de metal e plÃ¡stico resistente \r\n- Corpo de metal e plÃ¡stico resistente \r\n- Possui funÃ§Ã£o reverso \r\n- Botï', '- Moedor elÃ©trico de carne \r\n- TensÃ£o: 220v \r\n- PotÃªncia: 500w \r\n- Com bandeja e pilÃ£o \r\n- 3 tipos de disco: fino, mÃ©dio e grosso \r\n- Corpo de metal e plÃ¡stico resistente \r\n- Corpo de metal e plÃ¡stico resistente \r\n- Possui funÃ§Ã£o reverso \r\n- Botï', '', '3', '50', '30', '20', '2018-04-26 02:30:04', 0, 1),
(60, '789815943142', '', 'Balde metÃ¡lico para concreto 10 litros ', '14.90', '', '', '', 0, '0', 'zarifer', 0, 1, 5, 0, 'Balde metÃ¡lico para concreto 10 litros \r\nBalde metÃ¡lico repuxado com 2 alÃ§as resistente sendo uma lateral.\r\nUtilizado muito em construÃ§Ã£o civil em andaimes ou lugares de difÃ­cil acesso.\r\nCor: MetÃ¡lico\r\nCapacidade: 10 litros', 'Balde metÃ¡lico para concreto 10 litros \r\nBalde metÃ¡lico repuxado com 2 alÃ§as resistente sendo uma lateral.\r\nUtilizado muito em construÃ§Ã£o civil em andaimes ou lugares de difÃ­cil acesso.\r\nCor: MetÃ¡lico\r\nCapacidade: 10 litros', '', '2.5', '34', '34', '22', '2018-04-26 02:30:04', 0, 1),
(61, '7898374500010', '', 'Balde para pedreiro PVC 12,0 litros', '9.90', '', '', '', 0, '0', 'ZUMPLAST', 0, 1, 5, 0, 'Balde para pedreiro PVC 12,0 litros\r\nProduzido em polipropileno\r\nAlÃ§a em AÃ§o 1045 galvanizado, esp. 4,5mm\r\nCapacidade: 12 litros\r\nCor: Preto.', 'Balde para pedreiro PVC 12,0 litros\r\nProduzido em polipropileno\r\nAlÃ§a em AÃ§o 1045 galvanizado, esp. 4,5mm\r\nCapacidade: 12 litros\r\nCor: Preto.', '', '0.520', '30', '30', '26', '2018-04-26 02:30:04', 0, 1),
(62, '7898918082095', '', 'Caixa de correio correspondÃªncias alumÃ­nio grande para grade', '79.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias alumÃ­nio grande para grade\r\nCaixa de correio para correspondÃªncias em geral, como cartas e revistas, podendo ser fixada na grade de sua residÃªncia pelo lado de dentro, proporcionando maior seguranÃ§a e comodidade ', 'Caixa de correio correspondÃªncias alumÃ­nio grande para grade\r\nCaixa de correio para correspondÃªncias em geral, como cartas e revistas, podendo ser fixada na grade de sua residÃªncia pelo lado de dentro, proporcionando maior seguranÃ§a e comodidade ', '', '0.457', '23', '11', '32', '2018-04-26 02:30:04', 0, 1),
(63, '7898918082019', '', 'Caixa de correio correspondÃªncias polipropileno para grade amarela', '64.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias polipropileno para grade amarela\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV ', 'Caixa de correio correspondÃªncias polipropileno para grade amarela\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV ', '', '0.762', '25', '10', '33', '2018-04-26 02:30:04', 0, 1),
(64, '7898918082392', '', 'Caixa de correio para grade ', '64.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias polipropileno para grade branca\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', 'Caixa de correio correspondÃªncias polipropileno para grade branca\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', '', '0.752', '25', '10', '33', '2018-04-26 02:30:04', 0, 1),
(65, '7898918082026', '', 'Caixa de correio correspondÃªncias polipropileno para grade cinza', '64.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias polipropileno para grade cinza\r\n\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', 'Caixa de correio correspondÃªncias polipropileno para grade cinza\r\n\r\nMaior abertura superior para colocaÃ§Ã£o de jornais e revistas\r\nTampa superior nÃ£o fica aberta\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', '', '0.7423', '23', '10', '33', '2018-04-26 02:30:04', 0, 1),
(66, '7898944033016', '', 'Caixa de correio correspondÃªncias polipropileno para pequena amarela', '24.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias polipropileno para grade pequena amarela\r\nAbertura frontal para colocaÃ§Ã£o de cartas\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', 'Caixa de correio correspondÃªncias polipropileno para grade pequena amarela\r\nAbertura frontal para colocaÃ§Ã£o de cartas\r\nFeita em material plÃ¡stico PE (polietileno) 100% virgem, com adiÃ§Ã£o de Anti UV', '', '0.317', '19', '13.5', '24.5', '2018-04-26 02:30:04', 0, 1),
(67, '7898918082125', '', 'Caixa de correio correspondÃªncias alumÃ­nio pequena para grade', '44.90', '', '', '', 0, '0', 'FLORINI', 0, 1, 5, 0, 'Caixa de correio correspondÃªncias alumÃ­nio pequena para grade.\r\nCaixa de correio para cartas, podendo ser fixada na grade de sua residÃªncia pelo lado de dentro, proporcionando maior seguranÃ§a e comodidade ao receber os correios.\r\n\r\nModelo: Grade\r\nCor:', 'Caixa de correio correspondÃªncias alumÃ­nio pequena para grade.\r\nCaixa de correio para cartas, podendo ser fixada na grade de sua residÃªncia pelo lado de dentro, proporcionando maior seguranÃ§a e comodidade ao receber os correios.\r\n\r\nModelo: Grade\r\nCor:', '', '0.212', '20', '16', '9', '2018-04-26 02:30:04', 0, 1),
(68, '7899009026356', '', 'Caixa de Massa 20 litros PlÃ¡stica', '14.90', '', '', '', 0, '0', 'WORKER', 0, 1, 5, 0, 'Caixa de massa 20 litros plÃ¡stica preta\r\nUtilizada na construÃ§Ã£o civil para preparo de massa e afins.\r\n', 'Caixa de massa 20 litros plÃ¡stica preta\r\nUtilizada na construÃ§Ã£o civil para preparo de massa e afins.\r\n', '', '0.602', '60', '37', '14', '2018-04-26 02:30:04', 0, 1),
(69, '7898374500294', '', 'Caixa de massa 40 litros plastica preta', '39.90', '', '', '', 0, '0', 'ZUMPLAST', 0, 1, 5, 0, 'Caixa de massa 40 litros plastica preta reforÃ§ada.\r\nUtilizada na construÃ§Ã£o civil para preparo de masa e afins.', 'Caixa de massa 40 litros plastica preta reforÃ§ada.\r\nUtilizada na construÃ§Ã£o civil para preparo de masa e afins.', '', '1.600', '66', '44', '18', '2018-04-26 02:30:04', 0, 1),
(70, '789638092557', '', 'CaÃ§amba para tinta Atlas Profimast 10 litros pvc', '24.90', '', '', '', 0, '0', 'ATLAS', 0, 1, 5, 0, 'CaÃ§amba para tinta Atlas Profimast 10 litros pvc\r\nCaÃ§amba para pintura\r\nCor: Preta\r\nFabricada em plÃ¡stico de alta resistÃªncia\r\nCapacidade: 10 Litros\r\nPossui: Medidor de litros\r\nDupla Ã¡rea de rolagem\r\nAlÃ§a reforÃ§ada com gancho para fixaÃ§Ã£o em esca', 'CaÃ§amba para tinta Atlas Profimast 10 litros pvc\r\nCaÃ§amba para pintura\r\nCor: Preta\r\nFabricada em plÃ¡stico de alta resistÃªncia\r\nCapacidade: 10 Litros\r\nPossui: Medidor de litros\r\nDupla Ã¡rea de rolagem\r\nAlÃ§a reforÃ§ada com gancho para fixaÃ§Ã£o em esca', '', '0.505', '33', '23', '20', '2018-04-26 02:30:04', 0, 1),
(71, '7898601572292', '', 'Garrafa Decorativa Caveira Arriba', '39.95', '', '', '', 0, '0', 'Unika', 0, 1, 5, 0, 'Linda garrafa em vidro decorada com motivos de caveira mexicana', 'Linda garrafa em vidro decorada com motivos de caveira mexicana', '', '0.200', '8', '8', '33', '2018-04-26 02:30:04', 0, 1),
(72, '7898904534133', '', 'Elastil borracha termoplastica', '19.95', '', '', '', 0, '0', 'elastil', 0, 1, 5, 0, 'Ã‰ um selante de fÃ¡cil aplicaÃ§Ã£o, composto por borracha sintÃ©tica e solventes orgÃ¢nicos, que possui Ã³timas propriedades quÃ­micas, alto poder de aderÃªncia e flexibilidade\r\nAplicaÃ§Ã£o : Utilizado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas, rufos, toldo', 'Ã‰ um selante de fÃ¡cil aplicaÃ§Ã£o, composto por borracha sintÃ©tica e solventes orgÃ¢nicos, que possui Ã³timas propriedades quÃ­micas, alto poder de aderÃªncia e flexibilidade\r\nAplicaÃ§Ã£o : Utilizado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas, rufos, toldo', '', '0.310', '5', '5', '24', '2018-04-26 02:30:04', 0, 1),
(73, '000000000000000000000000', '', 'Suporte Veicular', '43', '', '', '', 0, '0', 'exbom', 0, 10, 5, 0, 'suporte veicular', 'suporte veicular', '', '0.5', '10', '10', '10', '2018-04-26 02:30:04', 0, 0),
(74, '179324', '', 'Carregador Portatil 7000Mah', '89.95', '', '69.95', '', 1, '0', 'ADF', 0, 1, 5, 0, 'Bateria extra que possibilita vocÃª carregar seu celular em qualquer lugar', 'Bateria extra que possibilita vocÃª carregar seu celular em qualquer lugar', '', '2', '19', '9', '3.5', '2018-04-26 02:30:04', 0, 1),
(75, '7202160', '', 'Tv Box 4k', '249.95', '', '', '', 0, '0', 'OTT', 0, 1, 5, 0, 'Transforma sua tv em smart tv com acesso as principais mÃ­dias sociais e Netflix', 'Transforma sua tv em smart tv com acesso as principais mÃ­dias sociais e Netflix', '', '.100', '20', '15', '5', '2018-04-26 02:30:04', 0, 0),
(76, '0000000192446', '', 'Caixa de Som TG 112 Bluetooth ', '149.95', '', '', '', 0, '0', 'BT', 0, 1, 5, 0, 'Distancia Bluetooth 10 M\r\nsaÃ­da 2 x 5w\r\nBateria 3,7v / 1200mah\r\n\r\n', 'Distancia Bluetooth 10 M\r\nsaÃ­da 2 x 5w\r\nBateria 3,7v / 1200mah\r\n\r\n', '', '150', '16.5', '8.8', '8.8', '2018-04-26 02:30:04', 0, 1),
(77, '859184004010', '', 'Suporte para celular Pop Socket', '14.99', '', '', '', 0, '0', 'Pop socket', 0, 1, 5, 0, 'Diversas Cores\r\nNÃ£o Ã© possÃ­vel escolher a cor\r\n', 'Diversas Cores\r\nNÃ£o Ã© possÃ­vel escolher a cor\r\n', '', '.05', '16.50', '8.00', '1.00', '2018-04-26 02:30:04', 0, 1),
(78, '40280688', '', 'Medidor de PressÃ£o Arterial EletrÃ´nico ', '139.95', '0.00', '0.00', '139.95', 0, '0', 'M.N.Y', 0, 1, 5, 1, 'Medidor de pressÃ£o arterial eletrÃ´nico \r\nTela LCD\r\nMÃ©todo Oscilometria\r\nPilha AAA\r\n', '<p>Medidor de press&atilde;o arterial eletr&ocirc;nico Tela LCD M&eacute;todo Oscilometria Pilha AAA</p>', '', '5', '7.85', '6.85', '3', '2018-07-25 02:30:40', 0, 1),
(79, '6901708801309', '', 'CÃ¢mera de SeguranÃ§a 3D', '269.95', '', '199.95', '', 1, '5', 'VR CAM ', 0, 1, 5, 1, 'CÃ¢mera PanorÃ¢mica 3D Wi-fi Imagem Olho de Peixe ', '<p>C&acirc;mera Panor&acirc;mica 3D Wi-fi Imagem Olho de Peixe</p>', '', '0.114', '18', '10', '9', '2018-05-14 04:14:41', 0, 1),
(80, '7899670805809', '', 'MÃ¡quina em AÃ§o Inox para Massas', '119.75', '', '', '', 0, '0', 'Unihome', 0, 1, 5, 0, '-Rolos ajustÃ¡veis para amassar \r\n- Sete ajustes de espessura \r\n- Manivela e grampo removÃ­veis \r\n- InstruÃ§Ãµes e receitas ', '-Rolos ajustÃ¡veis para amassar \r\n- Sete ajustes de espessura \r\n- Manivela e grampo removÃ­veis \r\n- InstruÃ§Ãµes e receitas ', '', '2.58', '20.5', '20', '15', '2018-04-26 02:30:04', 0, 0),
(81, '78997187363', '', 'Conversor Digital para TV', '119.95', '', '99.95', '', 1, '0', 'Infokit', 0, 1, 5, 0, 'Acompaha controle remoto \r\nSD Full - Full HD 180 pixel \r\nUSB para PVR \r\n', 'Acompaha controle remoto \r\nSD Full - Full HD 180 pixel \r\nUSB para PVR \r\n', '', '0.534', '19', '17', '5', '2018-04-26 02:30:04', 0, 1),
(82, '021366', '', 'CÃ¢mera wireless Internet Onvif', '199.95', '', '', '', 0, '0', 'onvif', 0, 1, 5, 0, 'TransmissÃ£o de imagens atravÃ©s da internet diretamente para seu celular\r\nPor ser Wifi pode ser colocado em qualquer local da sua casa que tenha acesso a internet. \r\nOnvif\r\nP2P qualidade de imagem em HD\r\nWifi', 'TransmissÃ£o de imagens atravÃ©s da internet diretamente para seu celular\r\nPor ser Wifi pode ser colocado em qualquer local da sua casa que tenha acesso a internet. \r\nOnvif\r\nP2P qualidade de imagem em HD\r\nWifi', '', '509', '17', '14', '12', '2018-04-26 02:30:04', 0, 0),
(83, 'xxxxxxxxxxxx', '', 'RelÃ³gio Despertador ', '59.95', '', '', '', 0, '0', 'Mxmidia', 0, 1, 5, 0, 'Despertador de Quartzo ', 'Despertador de Quartzo ', '', '3333333', '333333', '333333', '33333', '2018-04-26 02:30:04', 0, 1),
(84, '7899658378974', '', 'Copo para Salada ', '24.95', '', '', '', 0, '0', 'Arthouse', 0, 1, 5, 0, 'ContÃ©m: 1 Garfo\r\n1 Copo para Salada\r\n1 Compartimento para molhos com tampa ', 'ContÃ©m: 1 Garfo\r\n1 Copo para Salada\r\n1 Compartimento para molhos com tampa ', '', '0.132', '20', '12', '12', '2018-04-26 02:30:04', 0, 1),
(85, '6217250007084', '', 'Multicorte de Alimentos ', '119.95', '', '98.75', '', 1, '0', 'Genius', 0, 1, 5, 0, 'Conjunto de cortadores com 11 peÃ§as', 'Conjunto de cortadores com 11 peÃ§as', '', '-0.041', '29', '14', '12', '2018-04-26 02:30:04', 0, 1),
(86, '7899850306782', '', 'Espremedor de Frutas ', '26.95', '', '', '', 0, '0', 'Clink ', 0, 1, 5, 0, '400 ML \r\n2 em 1 Espremedor e cesto amassador ', '400 ML \r\n2 em 1 Espremedor e cesto amassador ', '', '-4.79', '15', '11', '8', '2018-04-26 02:30:04', 0, 1),
(87, '7898545640316', '', 'Cafeteira Expresso Italiana ', '79.95', '', '', '', 0, '0', 'KE home ', 0, 1, 5, 0, '9 xÃ­caras', '9 xÃ­caras', '', '0.559', '23', '12', '16', '2018-04-26 02:30:04', 0, 1),
(88, '7899009079147', '', 'Conjunto para Churrasco', '98.95', '', '', '', 0, '0', 'KALA', 0, 1, 5, 0, 'Acompanha: Garfo, faca, pegador e espÃ¡tula', 'Acompanha: Garfo, faca, pegador e espÃ¡tula', '', '3333', '333', '333', '333', '2018-04-26 02:30:04', 0, 1),
(89, '123', '', 'Tinta  AcrÃ­lico Mega Rendimento 18L', '198.95', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'O AcrÃ­lico Mega Rendimento Dacar Ã© uma tinta com grande consistÃªncia e alto poder de cobertura. Permite uma diluiÃ§Ã£o superior aos produtos existentes, sem perder as suas caracterÃ­sticas. Indicada para quem necessita de um bom desempenho em pinturas.', 'O AcrÃ­lico Mega Rendimento Dacar Ã© uma tinta com grande consistÃªncia e alto poder de cobertura. Permite uma diluiÃ§Ã£o superior aos produtos existentes, sem perder as suas caracterÃ­sticas. Indicada para quem necessita de um bom desempenho em pinturas.', '', '18.60', '30', '30', '40', '2018-04-26 02:30:04', 0, 1),
(90, '123', '', 'AcrÃ­lico Premium Emborrachado 18 Lt', '289.75', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'EXTERIORES E INTERIORES\r\nPELÃCULA IMPERMEÃVEL\r\nFLEXÃVEL\r\nCOBRE TRINCAS E FISSURAS\r\nANTIMOFO\r\n\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):40 m2 a 55 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):200 m2 a 275 m2 por demÃ£o\r\nA', 'EXTERIORES E INTERIORES\r\nPELÃCULA IMPERMEÃVEL\r\nFLEXÃVEL\r\nCOBRE TRINCAS E FISSURAS\r\nANTIMOFO\r\n\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):40 m2 a 55 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):200 m2 a 275 m2 por demÃ£o\r\nA', '', '18.60', '30', '30', '40', '2018-04-26 02:30:04', 0, 1),
(91, '123', '', 'AcrÃ­lico Premium Emborrachado 3,6 Lt', '78.99', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'EXTERIORES E INTERIORES\r\nPELÃCULA IMPERMEÃVEL\r\nFLEXÃVEL\r\nCOBRE TRINCAS E FISSURAS\r\nANTIMOFO\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):40 m2 a 55 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):200 m2 a 275 m2 por demÃ£o\r\nApl', 'EXTERIORES E INTERIORES\r\nPELÃCULA IMPERMEÃVEL\r\nFLEXÃVEL\r\nCOBRE TRINCAS E FISSURAS\r\nANTIMOFO\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):40 m2 a 55 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):200 m2 a 275 m2 por demÃ£o\r\nApl', '', '1', '1', '1', '1', '2018-04-26 02:30:04', 0, 1),
(92, '1234', '', 'Dacar Mega Rendimento 3,6 Lt', '42.99', '', '', '', 0, '0', 'DACAR', 0, 1, 5, 0, 'EXTERIORES E INTERIORES \r\nMEGA VISCOSIDADE\r\n80% DILUIÃ‡ÃƒO\r\nEXCELENTE COBERTURA\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):60 m2 a 100 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):300 m2 a 500 m2 por demÃ£o\r\nAplicaÃ§Ã£o\r\nUtil', 'EXTERIORES E INTERIORES \r\nMEGA VISCOSIDADE\r\n80% DILUIÃ‡ÃƒO\r\nEXCELENTE COBERTURA\r\nInformaÃ§Ãµes TÃ©cnicasEmbalagens/Rendimento GalÃ£o(Ãµes) 3,6 litros (3,6 L):60 m2 a 100 m2 por demÃ£o\r\nLata(s) 18 litros (18 L):300 m2 a 500 m2 por demÃ£o\r\nAplicaÃ§Ã£o\r\nUtil', '', '1', '1', '1', '1', '2018-04-26 02:30:04', 0, 1),
(93, '7891249074555', '', 'Fechadura AÃ§o Inox 40mm Banheiro Premium 2800/11 ', '34.99', '', '', '', 0, '0', 'alianÃ§a', 0, 1, 5, 0, 'Fechadura AÃ§o Inox 40mm para Banheiro Premium 2800/11\r\n', 'Fechadura AÃ§o Inox 40mm para Banheiro Premium 2800/11\r\n', '', '0.468', '20', '9', '5', '2018-04-26 02:30:04', 0, 1),
(94, '7891249061234', '', 'Fechadura POP Interna  2700/70 AlianÃ§a', '59.99', '', '', '', 0, '0', 'AlianÃ§a', 0, 1, 5, 0, 'Fechadura POP Interna  2700/70\r\nGrau de seguranÃ§a mÃ©dio\r\nResistÃªncia a CorrosÃ£o: 1', 'Fechadura POP Interna  2700/70\r\nGrau de seguranÃ§a mÃ©dio\r\nResistÃªncia a CorrosÃ£o: 1', '', '0.485', '20', '9', '5', '2018-04-26 02:30:04', 0, 1),
(95, '7899384907042', '', 'Fixador de porta magnÃ©tico ', '34.75', '', '', '', 0, '0', 'Worker', 0, 1, 5, 0, 'Fixador de porta magnÃ©tico niquelado\r\nCorpo  em aluminio fundido \r\nCom sistema de fixaÃ§Ã£o magnÃ©tico \r\nAcompanha uma chave allen 2/32, 2 buchas 6mm, 3 parafusos de madeira phillips', 'Fixador de porta magnÃ©tico niquelado\r\nCorpo  em aluminio fundido \r\nCom sistema de fixaÃ§Ã£o magnÃ©tico \r\nAcompanha uma chave allen 2/32, 2 buchas 6mm, 3 parafusos de madeira phillips', '', '0.152', '17', '11', '5', '2018-04-26 02:30:04', 0, 1),
(96, '7892327010120', '', 'Fechadura Para Porta de Correr Bico de Papagaio', '49.90', '', '', '', 0, '0', 'Soprano', 0, 1, 5, 0, 'Fechadura para porta de correr bico de papagaio', 'Fechadura para porta de correr bico de papagaio', '', '0.343', '20', '6', '4', '2018-04-26 02:30:04', 0, 1),
(97, '7897186001715', '', 'Visor para Porta ', '18.99', '', '', '', 0, '0', 'Western', 0, 1, 5, 0, 'Visor para Porta Cromado', 'Visor para Porta Cromado', '', '0.024', '13', '9.5', '3', '2018-04-26 02:30:04', 0, 1),
(98, '7893858108102', '', 'Fechadura Auxiliar 1001 - tetrachave', '69.75', '', '', '', 0, '0', 'STAM', 0, 1, 5, 0, 'Fechadura Auxiliar 1001 - Tetra\r\nAcompanha 4 chaves', 'Fechadura Auxiliar 1001 - Tetra\r\nAcompanha 4 chaves', '', '0.361', '15', '8.5', '3', '2018-04-26 02:30:04', 0, 1),
(99, '7893946488864', '', 'Fecho Pega LadrÃ£o ', '21.99', '', '', '', 0, '0', 'Vonder', 0, 1, 5, 0, 'Fecho Pega LadrÃ£o Vonder\r\nMaterial em aÃ§o carbono acabamento zincado\r\nAcompanha 7 parafusos', 'Fecho Pega LadrÃ£o Vonder\r\nMaterial em aÃ§o carbono acabamento zincado\r\nAcompanha 7 parafusos', '', '0.089', '13', '9', '2.5', '2018-04-26 02:30:04', 0, 1),
(100, '7899850306737', '', 'Saco para lavadora de Roupas', '9.95', '', '4.99', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Saco para lavadora de Roupas com cesto especial para sutiÃ£s\r\nProtege e evita bolinhas', 'Saco para lavadora de Roupas com cesto especial para sutiÃ£s\r\nProtege e evita bolinhas', '', '0.034', '25', '17', '2', '2018-04-26 02:30:04', 0, 1),
(101, '7888007064354', '', 'Mini depilador eletrico aparador de sobrancelha ', '29.95', '', '', '', 0, '0', 'Skysuper', 0, 1, 5, 0, 'Mini depilador eletrico aparador de sobrancelha \r\nPara retirar gentilmente os pelos indesejÃ¡veis\r\nUtiliza 1 pilha AAA 1,5 v (NÃ£o inclusa)\r\nDisponÃ­vel apenas na cor rosa', 'Mini depilador eletrico aparador de sobrancelha \r\nPara retirar gentilmente os pelos indesejÃ¡veis\r\nUtiliza 1 pilha AAA 1,5 v (NÃ£o inclusa)\r\nDisponÃ­vel apenas na cor rosa', '', '0.043', '19', '12', '3', '2018-04-26 02:30:04', 0, 1),
(102, '7899850305310', '', 'Seladora de Embalagens Clink', '14.99', '', '8.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Seladora de Embalagens Clink com ima para prender na geladeira\r\nIdeal para fechar embalagens de plastico como, pacote de bolacha, massas, etÃ§.\r\nFunciona com 2 pilhas AA nÃ£o incluÃ­das', 'Seladora de Embalagens Clink com ima para prender na geladeira\r\nIdeal para fechar embalagens de plastico como, pacote de bolacha, massas, etÃ§.\r\nFunciona com 2 pilhas AA nÃ£o incluÃ­das', '', '0.058', '10', '6', '5', '2018-04-26 02:30:04', 0, 1),
(103, '1076513001710', '', 'Cinzeiro de vidro caveira', '19.95', '', '', '', 0, '0', 'Diversos', 0, 1, 5, 0, 'Cinzeiro de vidro caveira', 'Cinzeiro de vidro caveira', '', '0.276', '10', '8', '4', '2018-04-26 02:30:04', 0, 1),
(104, '7898574335825', '', 'Lixeira para Carro', '29.95', '', '', '', 0, '0', 'Reliza', 0, 1, 5, 0, 'Lixeira para Carro em neoprene\r\nMaior espaÃ§o para armazenamento do lixo do seu carro\r\nMaterial lavÃ¡vel com agua', 'Lixeira para Carro em neoprene\r\nMaior espaÃ§o para armazenamento do lixo do seu carro\r\nMaterial lavÃ¡vel com agua', '', '0.040', '25', '20', '1', '2018-04-26 02:30:04', 0, 1),
(105, '7896396105176', '', 'Capa para carro Plastleo tamanho G', '99.95', '', '79.95', '', 1, '0', 'Plastleo ', 0, 1, 5, 0, 'Capa para carro Plastleo tamanho G\r\n100% polietileno\r\nIdeal para carros grandes como tucson, Fusion, Jetta, Versailles, santana, Quantum', 'Capa para carro Plastleo tamanho G\r\n100% polietileno\r\nIdeal para carros grandes como tucson, Fusion, Jetta, Versailles, santana, Quantum', '', '1.229', '42', '32', '22', '2018-04-26 02:30:04', 0, 1),
(106, '7896396105183', '', 'Capa para moto e Scooter ', '49.95', '', '38.99', '', 1, '0', 'Plastleo', 0, 1, 5, 0, 'Capa para moto e Scooter \r\nideal para motos atÃ© 500cc\r\nTamanho 2,10m x 1,25m\r\n100% polietileno ', 'Capa para moto e Scooter \r\nideal para motos atÃ© 500cc\r\nTamanho 2,10m x 1,25m\r\n100% polietileno ', '', '0.397', '32', '28', '6', '2018-04-26 02:30:04', 0, 1),
(107, '7899527131457', '', 'Capa para bicicleta ', '28.95', '', '', '', 0, '0', 'Wellmix', 0, 1, 5, 0, 'Capa para bicicleta \r\nProtege contra riscos e o tempo\r\nFÃ¡cil de limpar\r\nTamanho 2,0m x 1,0m\r\n100% polietileno', 'Capa para bicicleta \r\nProtege contra riscos e o tempo\r\nFÃ¡cil de limpar\r\nTamanho 2,0m x 1,0m\r\n100% polietileno', '', '0.112', '27', '20', '2', '2018-04-26 02:30:04', 0, 1),
(108, '7899206157419', '', 'Cabo para bateria 600 amperes 2,5m', '69.95', '', '', '', 0, '0', 'Bestfer', 0, 1, 5, 0, 'Cabo para bateria 600 amperes\r\nIdeal para fazer transferÃªncia de carga utilizando outra bateria\r\nCabo reforÃ§ado  \r\nComprimento: 2,5m', 'Cabo para bateria 600 amperes\r\nIdeal para fazer transferÃªncia de carga utilizando outra bateria\r\nCabo reforÃ§ado  \r\nComprimento: 2,5m', '', '0.914', '10', '10', '5', '2018-04-26 02:30:04', 0, 1),
(109, '7899206165544', '', 'Kit reparo de pneu', '19.95', '', '', '', 0, '0', 'Bestfer', 0, 1, 5, 0, 'Kit reparo de pneu\r\nContÃ©m: 1 escareador, 1 agulha de inserÃ§Ã£o de remendo, 1 tubo de cola, e 3 remendos de borracha ', 'Kit reparo de pneu\r\nContÃ©m: 1 escareador, 1 agulha de inserÃ§Ã£o de remendo, 1 tubo de cola, e 3 remendos de borracha ', '', '0.130', '24', '14', '2', '2018-04-26 02:30:04', 0, 1),
(110, '7866889907211', '', 'LuminÃ¡ria com Haste FlexÃ­vel com Prendedor', '69.95', '', '49.95', '', 1, '0', 'Novo Seculo', 0, 1, 5, 0, 'LuminÃ¡ria com haste flexÃ­vel com prendedor\r\nDiversas cores\r\nPode ser fixada em diversas superfÃ­cies', 'LuminÃ¡ria com haste flexÃ­vel com prendedor\r\nDiversas cores\r\nPode ser fixada em diversas superfÃ­cies', '', '0.332', '18', '17', '11', '2018-04-26 02:30:04', 0, 1),
(111, '7899755626602', '', 'Porta sabonete LÃ­quido RETRÃ”', '49.95', '', '', '', 0, '0', 'Wincy', 0, 1, 5, 0, 'Porta sabonete LÃ­quido RETRÃ”\r\nGabinete com Esponja \r\n', 'Porta sabonete LÃ­quido RETRÃ”\r\nGabinete com Esponja \r\n', '', '0.382', '13', '13', '11', '2018-04-26 02:30:04', 0, 1),
(112, '038586', '', 'Torre Paris decorativa ', '39.95', '', '', '', 0, '0', 'Interponte', 0, 1, 5, 0, 'Torre Paris decorativa ', 'Torre Paris decorativa ', '', '0.179', '26', '11', '10', '2018-04-26 02:30:04', 0, 1),
(113, '248707', '', 'Caixa de som a prova de Ã¡gua BLUETOOTH', '69.95', '', '', '', 0, '0', 'Diversos', 0, 1, 5, 0, 'Caixa de som a prova de Ã¡gua BLUETOOTH\r\n', 'Caixa de som a prova de Ã¡gua BLUETOOTH\r\n', '', '0.180', '7', '10', '7', '2018-04-26 02:30:04', 0, 1),
(114, '7899755658641', '', 'Gancho de Parede Inox', '12.99', '', '', '', 0, '0', 'Wincy', 0, 1, 5, 0, 'Gancho de parede inox\r\n6 peÃ§as\r\nCapacidade: 1,5 KG', 'Gancho de parede inox\r\n6 peÃ§as\r\nCapacidade: 1,5 KG', '', '0.028', '18', '9', '3', '2018-04-26 02:30:04', 0, 1),
(115, '7898555808973', '', 'Organizador de Fios e Cabos', '9.75', '', '', '', 0, '0', 'Clink', 0, 1, 5, 0, 'Organizador de fios e cabos\r\n8,1x 1cm\r\n3 peÃ§as', 'Organizador de fios e cabos\r\n8,1x 1cm\r\n3 peÃ§as', '', '0.030', '15', '10', '3', '2018-04-26 02:30:04', 0, 1),
(116, '7899850302906', '', 'Protetor adesivo anti-impacto', '9.95', '', '', '', 0, '0', 'Clink', 0, 1, 5, 0, 'Protetor adesivo anti-impacto\r\n2CM\r\n12 peÃ§as', 'Protetor adesivo anti-impacto\r\n2CM\r\n12 peÃ§as', '', '0.030', '15', '12.5', '2', '2018-04-26 02:30:04', 0, 1),
(117, '7898904534126', '', 'Elastil borracha termoplastica ( branco ) 265g', '22.90', '', '', '', 0, '0', 'elastil', 0, 1, 5, 0, 'Elastil borracha termoplastica ( branco ) 265g\r\n\r\nSelante composto por borracha sintÃ©tica e solventes orgÃ¢nica com alto poder de aderÃªncia e flexibilidade. Produto indicado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas,rufos,toldos,tubulaÃ§Ãµes de ar condiciona', 'Elastil borracha termoplastica ( branco ) 265g\r\n\r\nSelante composto por borracha sintÃ©tica e solventes orgÃ¢nica com alto poder de aderÃªncia e flexibilidade. Produto indicado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas,rufos,toldos,tubulaÃ§Ãµes de ar condiciona', '', '0.313', '5', '5', '23.5', '2018-04-26 02:30:04', 0, 1),
(118, '7898904534065', '', 'Elastil borracha termoplÃ¡stica ( cinza ) 265g', '22.90', '', '', '', 0, '0', 'Elastil', 0, 1, 5, 0, ' Composto por borracha sintÃ©tica e solventes orgÃ¢nica com alto poder de aderÃªncia e flexibilidade. \r\nIndicado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas,rufos,toldos,tubulaÃ§Ãµes.', ' Composto por borracha sintÃ©tica e solventes orgÃ¢nica com alto poder de aderÃªncia e flexibilidade. \r\nIndicado para vedaÃ§Ã£o e fixaÃ§Ã£o de calhas,rufos,toldos,tubulaÃ§Ãµes.', '', '0.313', '5', '5', '23.5', '2018-04-26 02:30:04', 0, 1),
(119, '7896531906590', '', 'Kit Higiene ', '9.95', '', '', '', 0, '0', 'Azpr', 0, 1, 5, 0, 'Kit higiene \r\nsacos plÃ¡sticos para remoÃ§Ã£o de dejetos\r\nContÃ©m 3 rolos com 15 sacos cada rolo', 'Kit higiene \r\nsacos plÃ¡sticos para remoÃ§Ã£o de dejetos\r\nContÃ©m 3 rolos com 15 sacos cada rolo', '', '0.066', '16.5', '10', '3', '2018-04-26 02:30:04', 0, 1),
(120, '7896531908419', '', 'Bico Bebedouro', '29.95', '', '', '', 0, '0', 'Azpr', 0, 1, 5, 0, 'Bico bebedouro\r\nlambe-lambe automÃ¡tico\r\nbico inoxidÃ¡vel', 'Bico bebedouro\r\nlambe-lambe automÃ¡tico\r\nbico inoxidÃ¡vel', '', '0.116', '20', '14', '8', '2018-04-26 02:30:04', 0, 1),
(121, '500193', '', 'Osso ComestÃ­vel', '12.99', '', '', '', 0, '0', 'Rei do Osso', 0, 1, 5, 0, 'Osso comestÃ­vel \r\nOsso nÃ³ G', 'Osso comestÃ­vel \r\nOsso nÃ³ G', '', '0.065', '20', '13.5', '5', '2018-04-26 02:30:04', 0, 1),
(122, '7896183301491', '', 'Pipi educador sanitÃ¡rio', '38.99', '', '', '', 0, '0', 'Sanol dog', 0, 1, 5, 0, 'Pipi educador sanitÃ¡rio \r\n20 ml\r\nAuxilia na educaÃ§Ã£o sanitÃ¡ria de filhotes ', 'Pipi educador sanitÃ¡rio \r\n20 ml\r\nAuxilia na educaÃ§Ã£o sanitÃ¡ria de filhotes ', '', '0.039', '16.5', '10', '3.5', '2018-04-26 02:30:04', 0, 1),
(123, '7896553954548', '', 'Petbox ', '69.95', '', '49.95', '', 1, '0', 'Coza Pets', 0, 1, 5, 0, 'Petbox \r\nContÃ©m 7 peÃ§as \r\n1Tapete\r\n1Petpote\r\n1Cesta organizadora\r\n2Bottons\r\n1Bebedouro\r\n1Comedouro \r\n\r\n', 'Petbox \r\nContÃ©m 7 peÃ§as \r\n1Tapete\r\n1Petpote\r\n1Cesta organizadora\r\n2Bottons\r\n1Bebedouro\r\n1Comedouro \r\n\r\n', '', '1.615', '34', '31', '20', '2018-04-26 02:30:04', 0, 1),
(124, '7897186076973', '', 'Brinquedo interativo para gatos', '69.95', '', '', '', 0, '0', 'Western', 0, 1, 5, 0, 'Brinquedo interativo para gatos\r\nRoda com ratinho\r\nCom arranhador na parte superior\r\n', 'Brinquedo interativo para gatos\r\nRoda com ratinho\r\nCom arranhador na parte superior\r\n', '', '0.408', '27', '27', '7.5', '2018-04-26 02:30:04', 0, 1),
(125, 'xxxxxxx', '', 'LuminÃ¡ria LED', '2222', '', '222', '', 1, '0', 'xxxx', 0, 1, 5, 0, 'xx', 'xx', '', '222', '222', '2222', '2222', '2018-04-26 02:30:04', 0, 0),
(126, '22284', '', 'LuminÃ¡ria Adesivo Flores', '79.95', '', '', '', 0, '0', 'Uatt Casa DecoraÃ§ao ', 0, 1, 5, 0, 'LuminÃ¡ria Adesivo de parede 110v ou 220v vem com fixadores de cabo adesivos', 'LuminÃ¡ria Adesivo de parede 110v ou 220v vem com fixadores de cabo adesivos', '', '68.2', '28', '27', '9', '2018-04-26 02:30:04', 0, 0),
(127, '7898552201258', '', 'Jogo Uno', '16.99', '', '', '', 0, '0', 'Compeg', 0, 1, 5, 0, 'Jogo de cartas baralho  UNO\r\n2 a 6 jogadores', 'Jogo de cartas baralho  UNO\r\n2 a 6 jogadores', '', '14.0', '15', '10', '3', '2018-04-26 02:30:04', 0, 0),
(128, '7898549369954', '', 'Jogo da Velha com copos de whisky', '69.95', '', '', '', 0, '0', 'Redstar sport', 0, 1, 5, 0, 'Jogo da velha com 9 copos de Drink ', 'Jogo da velha com 9 copos de Drink ', '', '13.82', '28', '28', '9', '2018-04-26 02:30:04', 0, 0),
(129, 'xxxxx', '', 'Fita Antiaderente Transparente', '1111', '', '111', '', 1, '0', 'Vonder', 0, 1, 5, 0, 'Fita antiaderente transparente', 'Fita antiaderente transparente', '', '2222', '222', '333', '222', '2018-04-26 02:30:04', 0, 0),
(130, '7891040075126', '', 'Fixa Forte', '18.99', '', '', '', 0, '0', 'Scotch', 0, 1, 5, 0, 'Fita adesiva extra forte 3m suporta atÃ© 400g', 'Fita adesiva extra forte 3m suporta atÃ© 400g', '', '31', '16', '9', '4', '2018-04-26 02:30:04', 0, 0),
(131, '7899206313716', '', 'Cafeteira tÃ©rmica', '139.95', '', '99.75', '', 1, '0', 'Eterny', 0, 1, 5, 0, 'Jarra elÃ©trica sem fio,Led indicador,alÃ§a emborrachada, filtro removÃ­vel , em aÃ§o inoxidÃ¡vel', 'Jarra elÃ©trica sem fio,Led indicador,alÃ§a emborrachada, filtro removÃ­vel , em aÃ§o inoxidÃ¡vel', '', '34.7', '23', '19', '22', '2018-04-26 02:30:04', 0, 1),
(132, '802308', '', 'BalanÃ§a EletrÃ´nica', '89.95', '', '59.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'BalanÃ§a digital\r\nCapacidade atÃ© 180 Kg\r\nMaterial : Vidro\r\n\r\n', 'BalanÃ§a digital\r\nCapacidade atÃ© 180 Kg\r\nMaterial : Vidro\r\n\r\n', '', '1.530', '30', '30', '4.5', '2018-04-26 02:30:04', 0, 1),
(133, '805705', '', 'Panela AlumÃ­nio Fundido Revestimento CerÃ¢mico 24CM', '169.95', '', '129.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Panela AlumÃ­nio Fundido Revestimento CerÃ¢mico 24 CM\r\nTampa de vidro com saÃ­da de vapor\r\nRevestimento cerÃ¢mico interno antiaderente, fÃ¡cil de limpar e resistente a calor e corrosÃ£o.\r\nProtetor de cabos em silicone.\r\n', 'Panela AlumÃ­nio Fundido Revestimento CerÃ¢mico 24 CM\r\nTampa de vidro com saÃ­da de vapor\r\nRevestimento cerÃ¢mico interno antiaderente, fÃ¡cil de limpar e resistente a calor e corrosÃ£o.\r\nProtetor de cabos em silicone.\r\n', '', '1.740', '26.5', '26.5', '13.5', '2018-04-26 02:30:04', 0, 1),
(134, '808492', '', 'Conjunto Porta Mantimentos Inox', '79.95', '', '49.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Conjunto Porta mantimentos inox\r\n4 PeÃ§as \r\nTampa hermÃ©tica\r\n', 'Conjunto Porta mantimentos inox\r\n4 PeÃ§as \r\nTampa hermÃ©tica\r\n', '', '0.920', '26.5', '23', '12.5', '2018-04-26 02:30:04', 0, 1),
(135, '309516', '', 'Squeeze  PlÃ¡stico Parede Dupla com Gel 550ml', '49.95', '', '39.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Squeeze  PlÃ¡stico Parede Dupla com Gel 550ml', 'Squeeze  PlÃ¡stico Parede Dupla com Gel 550ml', '', '0.453', '27', '9.5', '9', '2018-04-26 02:30:04', 0, 1),
(136, '807389', '', 'Lixeira Pedal AÃ§o Inox', '79.95', '', '49.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Lixeira Pedal AÃ§o Inox\r\n5 Litros\r\nBalde interno  que facilita a limpeza e retirada do lixo', 'Lixeira Pedal AÃ§o Inox\r\n5 Litros\r\nBalde interno  que facilita a limpeza e retirada do lixo', '', '1.038', '29', '22.5', '22', '2018-04-26 02:30:04', 0, 1),
(137, '303392', '', 'Travessa de Vidro 3L', '69.95', '', '48.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Travessa de Vidro 3L\r\nQuente ou frio\r\nPode ir ao freezer, lava-louÃ§a, forno e micro-ondas', 'Travessa de Vidro 3L\r\nQuente ou frio\r\nPode ir ao freezer, lava-louÃ§a, forno e micro-ondas', '', '1.713', '24.5', '40', '5.5', '2018-04-26 02:30:04', 0, 1);
INSERT INTO `pew_produtos` (`id`, `sku`, `codigo_barras`, `nome`, `preco`, `preco_custo`, `preco_promocao`, `preco_sugerido`, `promocao_ativa`, `desconto_relacionado`, `marca`, `id_cor`, `estoque`, `estoque_baixo`, `tempo_fabricacao`, `descricao_curta`, `descricao_longa`, `url_video`, `peso`, `comprimento`, `largura`, `altura`, `data`, `visualizacoes`, `status`) VALUES
(138, '302388', '', 'Leiteira de AlumÃ­nio 1,8 Litro', '69.95', '', '', '', 0, '0', 'Clink', 0, 1, 5, 0, 'Leiteira de AlumÃ­nio 1,8 Litro\r\nCabo revestidos com silicone.\r\nRevestimento cerÃ¢mico interno antiaderente, fÃ¡cil de limpar e resistente a calor e corrosÃ£o.\r\nProcesso de revestimento cerÃ¢mico feito a base de Ã¡gua e nÃ£o tÃ³xico.', 'Leiteira de AlumÃ­nio 1,8 Litro\r\nCabo revestidos com silicone.\r\nRevestimento cerÃ¢mico interno antiaderente, fÃ¡cil de limpar e resistente a calor e corrosÃ£o.\r\nProcesso de revestimento cerÃ¢mico feito a base de Ã¡gua e nÃ£o tÃ³xico.', '', '0.355', '13', '22', '16', '2018-04-26 02:30:04', 0, 1),
(139, '800199', '', 'MOP com Balde e EsfregÃ£o', '139.95', '', '99.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'MOP com Balde e EsfregÃ£o\r\nMop SPIN 360Â° \r\nacompanha refil \r\n', 'MOP com Balde e EsfregÃ£o\r\nMop SPIN 360Â° \r\nacompanha refil \r\n', '', '1.818', '26.5', '48.5', '26.5', '2018-04-26 02:30:04', 0, 1),
(140, '25119', '', 'Cabo para Bateria', '69.95', '', '', '', 0, '0', 'Bestfer', 0, 1, 5, 0, 'Cabo de bateria 600 amp. 2,5m c/ bolsa profissional ', 'Cabo de bateria 600 amp. 2,5m c/ bolsa profissional ', '', '32.5', '23', '24', '7', '2018-04-26 02:30:04', 0, 1),
(141, '5800550010772', '', 'Campainha sem fio', '49.95', '', '', '', 0, '0', 'Baoji', 0, 1, 5, 0, 'campainha sem fio com programaÃ§Ã£o de 32 musicas ', 'campainha sem fio com programaÃ§Ã£o de 32 musicas ', '', '4.8', '19', '15', '8', '2018-04-26 02:30:04', 0, 1),
(142, '7896689052989', '', 'Suporte de teto para bicicleta com sistema de elevaÃ§Ã£o', '149.75', '', '99.75', '', 1, '0', 'Bem fixa', 0, 1, 5, 0, 'Sistema de roldanas\r\nSeguro , resistente e ocupa menos espaÃ§o\r\nGanchos revestidos com borracha para evitar arranhÃµes\r\nPode ser instalado em tetos com atÃ© 4 metros de altura\r\nAcessÃ³rios para montagem fornecidos com o produto\r\nSuporta atÃ© 20kg\r\n', 'Sistema de roldanas\r\nSeguro , resistente e ocupa menos espaÃ§o\r\nGanchos revestidos com borracha para evitar arranhÃµes\r\nPode ser instalado em tetos com atÃ© 4 metros de altura\r\nAcessÃ³rios para montagem fornecidos com o produto\r\nSuporta atÃ© 20kg\r\n', '', '1.692', '26', '13', '10', '2018-04-26 02:30:04', 0, 1),
(143, '7896531900925', '', 'kit de sinalizaÃ§Ã£o para bike', '49.95', '', '39.95', '', 1, '0', 'AZPR', 0, 1, 5, 0, 'Item de seguranÃ§a essencial , funciona com 3 pilhas aaa\r\nFunÃ§Ãµes: Luz constante e pisca pisca.\r\nLanterna com 1 led\r\nSinalizador traseiro com 5 leds\r\n3 suportes para prender a lanterna na bicicleta', 'Item de seguranÃ§a essencial , funciona com 3 pilhas aaa\r\nFunÃ§Ãµes: Luz constante e pisca pisca.\r\nLanterna com 1 led\r\nSinalizador traseiro com 5 leds\r\n3 suportes para prender a lanterna na bicicleta', '', '0.150', '19.5', '15.5', '6', '2018-04-26 02:30:04', 0, 1),
(144, '176804', '', 'Suporte para Celular ', '38.99', '', '29.95', '', 1, '0', 'BMAX', 0, 1, 5, 0, 'Suporte para moto ou bicicleta a prova dÃ¡gua\r\nAdaptÃ¡vel em guidÃµes de atÃ© 38mm de diÃ¢metro\r\nPlÃ¡stico de alta durabilidade\r\nAjustÃ¡vel em vÃ¡rias posiÃ§Ãµes \r\nPelÃ­cula transparente\r\nVedaÃ§Ã£o contra chuva\r\nIndicado para Galaxy S2 , Moto E, Moto g1 e', 'Suporte para moto ou bicicleta a prova dÃ¡gua\r\nAdaptÃ¡vel em guidÃµes de atÃ© 38mm de diÃ¢metro\r\nPlÃ¡stico de alta durabilidade\r\nAjustÃ¡vel em vÃ¡rias posiÃ§Ãµes \r\nPelÃ­cula transparente\r\nVedaÃ§Ã£o contra chuva\r\nIndicado para Galaxy S2 , Moto E, Moto g1 e', '', '0.188', '27', '11.5', '4', '2018-04-26 02:30:04', 0, 1),
(145, '7897186020648', '', 'Kit Chave para bicicleta 15 pontas', '56.95', '', '39.95', '', 1, '0', 'Western', 0, 1, 5, 0, 'Kit de chave para bicicleta com 15 pontas, contendo: Adaptador do soquete, chave fenda 5mm, 3 soquetes 1/4 8mm, 9mm, 10mm,  3 chaves de boca de 8mm, 10mm e 15mm e mais 6 chaves allen 2mm, 2,5mm, 3mm, 4mm, 5mm, 6mm.', 'Kit de chave para bicicleta com 15 pontas, contendo: Adaptador do soquete, chave fenda 5mm, 3 soquetes 1/4 8mm, 9mm, 10mm,  3 chaves de boca de 8mm, 10mm e 15mm e mais 6 chaves allen 2mm, 2,5mm, 3mm, 4mm, 5mm, 6mm.', '', '278', '22.5', '15', '5.5', '2018-04-26 02:30:04', 0, 1),
(146, '7899850305754', '', 'Corrente anti furto para Moto ou bicicleta', '44.95', '', '39.95', '', 1, '0', 'Clink', 0, 1, 5, 0, 'Corrente anti furto de ferro e cobre, revestida com pvc para bicicleta ou moto de 22mm com 1 metro ', 'Corrente anti furto de ferro e cobre, revestida com pvc para bicicleta ou moto de 22mm com 1 metro ', '', '0.613', '24', '18', '5', '2018-04-26 02:30:04', 0, 1),
(147, '7896186105867', '', 'Luva esportiva antiderrapante', '29.95', '', '', '', 0, '0', 'Feimoshi', 0, 1, 5, 0, 'Luva esportiva antiderrapante para proteger mÃ£os e juntas', 'Luva esportiva antiderrapante para proteger mÃ£os e juntas', '', '0.076', '18', '9.5', '3', '2018-04-26 02:30:04', 0, 1),
(148, '701065', '', 'Bomba para encher pneu de bicicleta', '39.95', '', '', '', 0, '0', 'PUMP', 0, 1, 5, 0, 'Bomba para encher pneu de bicicleta contendo uma presilha e adaptador com bico para encher bola', 'Bomba para encher pneu de bicicleta contendo uma presilha e adaptador com bico para encher bola', '', '0.270', '40', '12', '6', '2018-04-26 02:30:04', 0, 1),
(149, '7899739288543', '', 'Kit reparo para bicicleta 8 peÃ§as ', '19.95', '', '', '', 0, '0', '123 Util', 0, 1, 5, 0, 'Kit reparo para bicicleta 8 peÃ§as contendo chave bola para apertar parafusos, espatula , cola e remendos', 'Kit reparo para bicicleta 8 peÃ§as contendo chave bola para apertar parafusos, espatula , cola e remendos', '', '0.085', '20', '12', '2', '2018-04-26 02:30:04', 0, 1),
(150, '7899737510356', '', 'Piscina InflÃ¡vel 2100 Litros', '199.75', '', '', '', 0, '0', 'Kala', 0, 1, 5, 0, 'Piscina InflÃ¡vel 2100 Litros\r\nTripla camada de resistÃªncia\r\nResistente e fÃ¡cil de montar\r\n', 'Piscina InflÃ¡vel 2100 Litros\r\nTripla camada de resistÃªncia\r\nResistente e fÃ¡cil de montar\r\n', '', '5.250', '36.5', '26.5', '24.5', '2018-04-26 02:30:04', 0, 1),
(151, '102180', '', 'Chuveiro de Jardim TelescÃ³pio com TripÃ©', '129.75', '', '0.00', '', 0, '0', 'WORKER', 27, 1, 1, 1, 'Chuveiro de Jardim TelescÃ³pio com TripÃ©', '<ul><li>F&aacute;cil montagem&nbsp;</li><li>Possui conector para engate r&aacute;pido de 1/2</li><li>V&aacute;lvula para controle de press&atilde;o da &aacute;gua</li><li>a altura pode ser regulada de 1,65m at&eacute; 2,40m</li><li>&acirc;ngulo ajustav&eacute;l do jato, varia&ccedil;&atilde;o do tamanho conforme a necessidade</li></ul>', '', '0.954', '93.5', '13', '14', '2018-05-03 11:12:40', 0, 1),
(152, '166695', '', 'Kit mangueira expansÃ­vel, esguicho e adaptador', '74.99', '', '0.00', '', 0, '0', 'Bestfer', 0, 1, 1, 0, 'Kit mangueira expansÃ­vel, esguicho e adaptador', '<ul><li>Adaptador- 3/4 para 1/2</li><li>Esguicho 7 fun&ccedil;&otilde;es</li><li>mangueira expans&iacute;vel : M&iacute;n 5m- M&aacute;x 15M</li></ul>', '', '0.542', '29.5', '12', '12', '2018-05-03 11:12:19', 0, 1),
(153, '790012', '', 'Raquete mata mosquito RecarregÃ¡vel', '39.95', '', '0.00', '', 0, '0', '', 0, 1, 1, 0, 'Bivolt : 127v-220v\r\n60 Hz - 1W', '<ul><li>Bateria regarreg&aacute;vel de chumbo-&aacute;cido, 400mAh</li><li>Adota um inovador circuito de descarga de duplas velocidade</li><li>consiste em 3 camadas de rede de metal, f&aacute;cil para pegar insetos</li><li>tempo de recarga completa da bateria: 10 horas</li></ul>', '', '0.245', '50', '21', '6', '2018-05-03 11:25:09', 0, 1),
(154, '994318', '', 'Mata mosquito repelente', '29.95', '', '0.00', '', 0, '0', 'Diversos', 0, 1, 1, 0, 'Mata mosquito repelente', '<ul><li>Para roedores,baratas,formigas e aranhas</li><li><p>Transforme&nbsp; sua casa em um campo de for&ccedil;a repelente de pragas</p></li></ul>', '', '0.08', '19.5', '15', '8', '2018-05-03 11:40:24', 0, 1),
(155, '000327', '', 'Colher de Frutas Tramontina', '34.99', '', '0.00', '', 0, '0', 'Diversos', 0, 1, 1, 0, 'Colher de Frutas Tramontina', '<ul><li>Colher de Frutas Tramontina</li><li>Composi&ccedil;&atilde;o: madeira e metais&nbsp;</li></ul>', '', '0.26', '25', '14', '15', '2018-05-03 11:53:21', 0, 1),
(156, '012629', '', 'Conjunto jardim 3 peÃ§as', '24.99', '', '0.00', '', 0, '0', 'Western', 0, 1, 1, 0, 'Conjunto jardim 3 peÃ§as', '<p>Conjunto jardim&nbsp;</p><p>Cont&eacute;m 3 pe&ccedil;as</p><ul><li>Pazinha</li><li>Garfo</li><li>Rastelo</li></ul><p>&nbsp;</p><p>&nbsp;</p>', '', '0.214', '25', '14', '5', '2018-05-03 01:56:37', 0, 1),
(157, '906767', '', 'Tesoura de poda para jardim', '29.95', '', '0.00', '', 0, '0', 'Azpr', 0, 1, 1, 0, 'Tesoura de poda para jardim', '<p>Tesoura de poda para jardim</p><ul><li>composi&ccedil;&atilde;o: A&ccedil;o carbono e Polipropileno</li><li>Tamanho 19cm</li></ul>', '', '0.13', '25', '8.5', '3', '2018-05-03 03:38:08', 0, 1),
(158, '057893', '', 'Esguicho pistola jet', '29.75', '', '0.00', '', 0, '0', 'Kala', 0, 1, 1, 0, 'Esguicho pistola jet 6 opÃ§Ãµes de jato', '<ul><li>6 op&ccedil;&otilde;es de jato</li><li>Pr&aacute;tico de usar</li><li>f&aacute;cil manuseio</li><li>Material: pl&aacute;stico PP + BS</li></ul>', '', '0.152', '25', '15.5', '6.5', '2018-05-03 04:20:59', 0, 1),
(159, '057886', '', 'RelÃ³gio temporizador ', '37.99', '', '0.00', '', 0, '0', 'WORKER', 0, 1, 1, 0, 'RelÃ³gio temporizador  para jardim', '<ul><li>Para regar jardins</li><li>5 a 120 minutos</li><li>N&atilde;o requer bateria&nbsp;</li></ul>', '', '0.179', '23', '14.5', '9', '2018-05-03 04:32:49', 0, 1),
(160, '079352', '', 'Pulverizador compressÃ£o prÃ©via', '89.75', '0.00', '0.00', '0.00', 0, '0', 'WORKER', 0, 1, 1, 1, 'Pulverizador compressÃ£o prÃ©via ', '<ul><li>5 Litros</li><li>Fivelas regul&aacute;veis</li><li>Bico com jato regul&aacute;vel</li></ul>', '', '1.356', '43.5', '19', '18.5', '2018-05-17 02:22:14', 0, 1),
(161, '464608', '', 'Aparador de Grama', '299.75', '', '0.00', '', 0, '0', 'Vonder', 0, 1, 1, 1, 'Aparador de Grama ag 1000W', '<ul><li><strong>P</strong><strong>ot&ecirc;ncia m&aacute;xima:</strong>&nbsp;1000 W</li><li><strong>Pot&ecirc;ncia nominal:</strong>&nbsp;580 W</li><li><strong>&Aacute;rea de corte do aparador de grama:</strong>&nbsp;250 mm</li><li><strong>Di&acirc;metro m&aacute;ximo do fio de nylon do aparador de grama:</strong>&nbsp;1,8 mm</li><li><strong>Comprimento aproximado do fio de nylon que acompanha o aparador:</strong>&nbsp;4 m</li><li><strong>Comprimento do cord&atilde;o el&eacute;trico:</strong>&nbsp;0,13 m</li><li><strong>Tens&atilde;o (V):</strong>&nbsp;127 V~</li><li><strong>Frequ&ecirc;ncia (Hz):</strong>&nbsp;60 Hz</li><li><strong>Rota&ccedil;&atilde;o do motor:</strong> 7.000 rpm</li></ul>', '', '3.122', '1.25', '23', '12.5', '2018-05-10 03:06:55', 0, 1),
(162, 'sdasdsadas', '', 'Produto teste checkout', '1.00', '1.00', '0.00', '1.00', 0, '5', '', 0, 1, 1, 1, 'sdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadas', '<p>sdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadas</p>', '', '0.3', '10', '10', '10', '2018-05-29 05:19:16', 0, 1),
(163, 'sdasdsadas2', '', 'Produto teste checkout 2', '0.50', '0.50', '0.00', '0.50', 0, '5', '', 0, 1, 1, 1, 'sdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadas', '<p>sdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadassdasdsadas</p>', '', '0.3', '10', '10', '10', '2018-05-30 01:42:32', 0, 1),
(164, '7898584834233', '', 'LAMPADA LED 9W', '16.99', '5.90', '9.99', '16.99', 1, '5', 'xxxx', 27, 1, 1, 0, 'Lampada de led L&D 9w  ', '<ul><li>Base E 27</li><li>Fluxo Luminoso 810 lm</li><li>Tens&atilde;o 100 - 240v</li></ul><p>&nbsp;</p>', '', '0.7', '7', '7', '12', '2018-06-21 01:11:42', 0, 1),
(165, '7885180910126', '', 'Bule de ChÃ¡ Oriental', '69.95', '23.15', '49.95', '69.95', 1, '5', 'xxxx', 0, 1, 1, 0, 'bule de chÃ¡ oriental de porcelana', '<p>bule de ch&aacute; oriental de porcelana com al&ccedil;a de palha e tela&nbsp;</p>', '', '0.65', '15', '14', '12.5', '2018-06-21 01:48:59', 0, 1),
(166, '7899850305686', '', 'cabide para lenÃ§os e cachecol', '29.95', '8.84', '24.99', '29.95', 1, '5', 'xxxx', 0, 1, 1, 0, 'cabide para lenÃ§os e cachecol', '<ul><li>Cabide para len&ccedil;os e cachecol 28 pe&ccedil;as metal e papel&nbsp;</li><li>Tamanho 72,5 x 36,5</li></ul>', '', '0.25', '36.5', '2', '72.5', '2018-06-21 02:55:17', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_produtos_relacionados`
--

CREATE TABLE `pew_produtos_relacionados` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_relacionado` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `pew_produtos_relacionados`
--

INSERT INTO `pew_produtos_relacionados` (`id`, `id_produto`, `id_relacionado`) VALUES
(89, 78, 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_subcategorias`
--

CREATE TABLE `pew_subcategorias` (
  `id` int(11) NOT NULL,
  `subcategoria` varchar(255) NOT NULL,
  `id_categoria` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_subcategorias`
--

INSERT INTO `pew_subcategorias` (`id`, `subcategoria`, `id_categoria`, `descricao`, `ref`, `data_controle`, `status`) VALUES
(2, 'Artigos automotivos', '20', '', 'artigos-automotivos', '2018-07-05 11:46:48', 1),
(3, 'Festa', '17', '', 'festa', '2018-07-20 10:33:44', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_subcategorias_produtos`
--

CREATE TABLE `pew_subcategorias_produtos` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_subcategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pew_usuarios_administrativos`
--

CREATE TABLE `pew_usuarios_administrativos` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `empresa` varchar(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pew_usuarios_administrativos`
--

INSERT INTO `pew_usuarios_administrativos` (`id`, `id_franquia`, `empresa`, `usuario`, `senha`, `email`, `nivel`) VALUES
(1, 1, 'Lar e Obra', 'lareobra', '5071edb03e659751e68e2a8f12d02c69', 'financeiro@lareobra.com.br', 1),
(12, 11, 'Lar e Obra', 'Frank', 'd3d1f180603e6dae9ce56d7c92be36ad', 'lareobra1@gmail.com', 2),
(13, 12, 'Lar e Obra', 'Rogerio', '08541bb36f049db6004fd98457138485', 'rogerio@efectusweb.com.br', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets_images`
--

CREATE TABLE `tickets_images` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets_messages`
--

CREATE TABLE `tickets_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` int(11) NOT NULL,
  `data_controle` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets_register`
--

CREATE TABLE `tickets_register` (
  `id` int(11) NOT NULL,
  `id_franquia` int(11) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `data_controle` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `franquias_lojas`
--
ALTER TABLE `franquias_lojas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `franquias_newsletter`
--
ALTER TABLE `franquias_newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `franquias_produtos`
--
ALTER TABLE `franquias_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `franquias_requisicoes`
--
ALTER TABLE `franquias_requisicoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_banners`
--
ALTER TABLE `pew_banners`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_carrinhos`
--
ALTER TABLE `pew_carrinhos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_categorias`
--
ALTER TABLE `pew_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_categorias_produtos`
--
ALTER TABLE `pew_categorias_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_categorias_vitrine`
--
ALTER TABLE `pew_categorias_vitrine`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_categoria_destaque`
--
ALTER TABLE `pew_categoria_destaque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_config_orcamentos`
--
ALTER TABLE `pew_config_orcamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_contatos`
--
ALTER TABLE `pew_contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_contatos_servicos`
--
ALTER TABLE `pew_contatos_servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_cores`
--
ALTER TABLE `pew_cores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_cores_relacionadas`
--
ALTER TABLE `pew_cores_relacionadas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_departamentos`
--
ALTER TABLE `pew_departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_departamentos_produtos`
--
ALTER TABLE `pew_departamentos_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_dicas`
--
ALTER TABLE `pew_dicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_enderecos`
--
ALTER TABLE `pew_enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_especificacoes_produtos`
--
ALTER TABLE `pew_especificacoes_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_especificacoes_tecnicas`
--
ALTER TABLE `pew_especificacoes_tecnicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_imagens_produtos`
--
ALTER TABLE `pew_imagens_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_links_menu`
--
ALTER TABLE `pew_links_menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_marcas`
--
ALTER TABLE `pew_marcas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_minha_conta`
--
ALTER TABLE `pew_minha_conta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `pew_newsletter`
--
ALTER TABLE `pew_newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_orcamentos`
--
ALTER TABLE `pew_orcamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_pedidos`
--
ALTER TABLE `pew_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_produtos`
--
ALTER TABLE `pew_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_produtos_relacionados`
--
ALTER TABLE `pew_produtos_relacionados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_subcategorias`
--
ALTER TABLE `pew_subcategorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_subcategorias_produtos`
--
ALTER TABLE `pew_subcategorias_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pew_usuarios_administrativos`
--
ALTER TABLE `pew_usuarios_administrativos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tickets_images`
--
ALTER TABLE `tickets_images`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tickets_messages`
--
ALTER TABLE `tickets_messages`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tickets_register`
--
ALTER TABLE `tickets_register`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `franquias_lojas`
--
ALTER TABLE `franquias_lojas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de tabela `franquias_newsletter`
--
ALTER TABLE `franquias_newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `franquias_produtos`
--
ALTER TABLE `franquias_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;
--
-- AUTO_INCREMENT de tabela `franquias_requisicoes`
--
ALTER TABLE `franquias_requisicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de tabela `pew_banners`
--
ALTER TABLE `pew_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de tabela `pew_carrinhos`
--
ALTER TABLE `pew_carrinhos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de tabela `pew_categorias`
--
ALTER TABLE `pew_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de tabela `pew_categorias_produtos`
--
ALTER TABLE `pew_categorias_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
--
-- AUTO_INCREMENT de tabela `pew_categorias_vitrine`
--
ALTER TABLE `pew_categorias_vitrine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de tabela `pew_categoria_destaque`
--
ALTER TABLE `pew_categoria_destaque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `pew_config_orcamentos`
--
ALTER TABLE `pew_config_orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `pew_contatos`
--
ALTER TABLE `pew_contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de tabela `pew_contatos_servicos`
--
ALTER TABLE `pew_contatos_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de tabela `pew_cores`
--
ALTER TABLE `pew_cores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de tabela `pew_cores_relacionadas`
--
ALTER TABLE `pew_cores_relacionadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de tabela `pew_departamentos`
--
ALTER TABLE `pew_departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de tabela `pew_departamentos_produtos`
--
ALTER TABLE `pew_departamentos_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;
--
-- AUTO_INCREMENT de tabela `pew_dicas`
--
ALTER TABLE `pew_dicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `pew_enderecos`
--
ALTER TABLE `pew_enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de tabela `pew_especificacoes_produtos`
--
ALTER TABLE `pew_especificacoes_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT de tabela `pew_especificacoes_tecnicas`
--
ALTER TABLE `pew_especificacoes_tecnicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de tabela `pew_imagens_produtos`
--
ALTER TABLE `pew_imagens_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;
--
-- AUTO_INCREMENT de tabela `pew_links_menu`
--
ALTER TABLE `pew_links_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT de tabela `pew_marcas`
--
ALTER TABLE `pew_marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;
--
-- AUTO_INCREMENT de tabela `pew_minha_conta`
--
ALTER TABLE `pew_minha_conta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT de tabela `pew_newsletter`
--
ALTER TABLE `pew_newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de tabela `pew_orcamentos`
--
ALTER TABLE `pew_orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT de tabela `pew_pedidos`
--
ALTER TABLE `pew_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `pew_produtos`
--
ALTER TABLE `pew_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;
--
-- AUTO_INCREMENT de tabela `pew_produtos_relacionados`
--
ALTER TABLE `pew_produtos_relacionados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT de tabela `pew_subcategorias`
--
ALTER TABLE `pew_subcategorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `pew_subcategorias_produtos`
--
ALTER TABLE `pew_subcategorias_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `pew_usuarios_administrativos`
--
ALTER TABLE `pew_usuarios_administrativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de tabela `tickets_images`
--
ALTER TABLE `tickets_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tickets_messages`
--
ALTER TABLE `tickets_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT de tabela `tickets_register`
--
ALTER TABLE `tickets_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
