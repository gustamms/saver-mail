-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.6-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para savermail
CREATE DATABASE IF NOT EXISTS `savermail` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `savermail`;

-- Copiando estrutura para tabela savermail.campanha
CREATE TABLE IF NOT EXISTS `campanha` (
  `idCampanha` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `descricao` varchar(60) NOT NULL,
  `idUsuario` int(10) unsigned NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A = ATIVO/ D = DESATIVADO/ E = ENVIADO',
  `corpo` text NOT NULL,
  PRIMARY KEY (`idCampanha`) USING BTREE,
  KEY `idx_campanha_idUsuario` (`idUsuario`) USING BTREE,
  CONSTRAINT `fk_campanha_idUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela savermail.campanhacontatos
CREATE TABLE IF NOT EXISTS `campanhacontatos` (
  `idCampanhaContatos` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `idContato` int(11) unsigned NOT NULL,
  `idCampanha` int(11) unsigned NOT NULL,
  `enviado` char(1) NOT NULL DEFAULT 'N' COMMENT 'S = Sim / N = Não',
  `visualizado` char(1) NOT NULL DEFAULT 'N' COMMENT 'S = Sim / N = Não',
  `hashVisualizado` varchar(60) NOT NULL,
  PRIMARY KEY (`idCampanhaContatos`) USING BTREE,
  UNIQUE KEY `hashVisualizado` (`hashVisualizado`),
  KEY `idx_campanhacontatos_idContato` (`idContato`) USING BTREE,
  KEY `idx_campanhacontatos_idCampanha` (`idCampanha`) USING BTREE,
  CONSTRAINT `fk_campanhacontatos_idCampanha` FOREIGN KEY (`idCampanha`) REFERENCES `campanha` (`idCampanha`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_campanhacontatos_idContato` FOREIGN KEY (`idContato`) REFERENCES `contato` (`idContato`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela savermail.contato
CREATE TABLE IF NOT EXISTS `contato` (
  `idContato` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `idUsuarioRespCadastrar` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idContato`) USING BTREE,
  KEY `idx_contato_idUsuarioRespCadastrar` (`idUsuarioRespCadastrar`) USING BTREE,
  CONSTRAINT `FK_contato_usuario` FOREIGN KEY (`idUsuarioRespCadastrar`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela savermail.envioemails
CREATE TABLE IF NOT EXISTS `envioemails` (
  `idEnvioEmails` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUsuario` int(11) unsigned NOT NULL,
  `idCampanha` int(11) unsigned NOT NULL,
  `idContato` int(11) unsigned NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'N' COMMENT 'N = NAO ENVIADO / E = ENVIADO / V = VISUALIZADO',
  PRIMARY KEY (`idEnvioEmails`) USING BTREE,
  KEY `idx_envioemails_idUsuario` (`idUsuario`) USING BTREE,
  KEY `idx_envioemails_idCampanha` (`idCampanha`) USING BTREE,
  KEY `idx_envioemails_idContato` (`idContato`) USING BTREE,
  CONSTRAINT `fk_envioemails_idCampanha` FOREIGN KEY (`idCampanha`) REFERENCES `campanha` (`idCampanha`) ON UPDATE CASCADE,
  CONSTRAINT `fk_envioemails_idContato` FOREIGN KEY (`idContato`) REFERENCES `contato` (`idContato`) ON UPDATE CASCADE,
  CONSTRAINT `fk_envioemails_idUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela savermail.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `idUsuario` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `confirmouEmail` char(1) NOT NULL DEFAULT 'F' COMMENT 'F = False / T = True',
  `linkConfirmacao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idUsuario`) USING BTREE,
  UNIQUE KEY `linkConfirmacao` (`linkConfirmacao`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
