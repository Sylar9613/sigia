-- Por Arian 1.1b
-- Generado el 21-06-2020 a las 09:06:24 por el usuario 'root'
-- Servidor: localhost:8000
-- MySQL Version: 5.5.5-10.1.28-MariaDB
-- PHP Version: 7.1.10
-- Base de datos: 'sigia'
-- Tablas: '25'
     
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `buscar_string` (`nombre_o_apellido` VARCHAR(120), `fuente` VARCHAR(120)) RETURNS INT(3) begin

RETURN if(locate(nombre_o_apellido,fuente)=0,0,1);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `devolver_anno` (`fecha_vence` VARCHAR(10)) RETURNS INT(2) BEGIN
return IF(EXTRACT(YEAR FROM curdate())-(LEFT(fecha_vence,4)+5)=0,1,0);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `edad` (`CI` VARCHAR(11)) RETURNS INT(11) BEGIN

return if(substr(curdate(),3,2) > left(CI,2), substr(curdate(),3,2) - left(CI,2),
left(curdate(),4)-1900-left(CI,2));

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `sexo` (`CI` VARCHAR(11)) RETURNS CHAR(1) CHARSET latin1 BEGIN

return if(substr(CI,10,1)%2=0,'M','F');

END$$

DELIMITER ;

            
-- 
-- Vaciado de tabla 'datos'
-- 
DROP TABLE IF EXISTS `datos`;
                        
--
-- Estructura de tabla para la tabla 'datos'
--

CREATE TABLE `datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `database_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'datos'
--

INSERT INTO `datos` (`id`, `database_filename`, `date`) VALUES 
('1', 'database20200420070504.sql', '2020-04-20 19:05:04'),
('4', 'database20200420095155.sql', '2020-04-20 21:51:56');

            
-- 
-- Vaciado de tabla 'accion_control'
-- 
DROP TABLE IF EXISTS `accion_control`;
                        
--
-- Estructura de tabla para la tabla 'accion_control'
--

CREATE TABLE `accion_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) DEFAULT NULL,
  `tipo_accion_id` int(11) DEFAULT NULL,
  `particularidad_id` int(11) DEFAULT NULL,
  `combustible_id` int(11) DEFAULT NULL,
  `orden_trabajo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `directivas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditor_plan` smallint(6) DEFAULT NULL,
  `auditor_real` smallint(6) DEFAULT NULL,
  `dias_plan` smallint(6) DEFAULT NULL,
  `dias_real` smallint(6) DEFAULT NULL,
  `auditor_xdia_plan` smallint(6) DEFAULT NULL,
  `auditor_xdia_real` smallint(6) DEFAULT NULL,
  `fecha_inicio_plan` date NOT NULL,
  `fecha_fin_plan` date NOT NULL,
  `fecha_inicio_real` date NOT NULL,
  `fecha_fin_real` date NOT NULL,
  `calificacion` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dano_cup` double DEFAULT NULL,
  `dano_cuc` double DEFAULT NULL,
  `dano_otra_moneda` double DEFAULT NULL,
  `plan_medidas` tinyint(1) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4FF9A0D9D5BD96DF` (`combustible_id`),
  KEY `IDX_4FF9A0D9DD25ED3B` (`tipo_accion_id`),
  KEY `IDX_4FF9A0D9545F6541` (`particularidad_id`),
  KEY `IDX_4FF9A0D96CA204EF` (`entidad_id`),
  CONSTRAINT `FK_4FF9A0D9545F6541` FOREIGN KEY (`particularidad_id`) REFERENCES `particularidad` (`id`),
  CONSTRAINT `FK_4FF9A0D96CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`),
  CONSTRAINT `FK_4FF9A0D9D5BD96DF` FOREIGN KEY (`combustible_id`) REFERENCES `combustible` (`id`),
  CONSTRAINT `FK_4FF9A0D9DD25ED3B` FOREIGN KEY (`tipo_accion_id`) REFERENCES `tipo_accion` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'accion_control'
--

INSERT INTO `accion_control` (`id`, `entidad_id`, `tipo_accion_id`, `particularidad_id`, `combustible_id`, `orden_trabajo`, `directivas`, `auditor_plan`, `auditor_real`, `dias_plan`, `dias_real`, `auditor_xdia_plan`, `auditor_xdia_real`, `fecha_inicio_plan`, `fecha_fin_plan`, `fecha_inicio_real`, `fecha_fin_real`, `calificacion`, `dano_cup`, `dano_cuc`, `dano_otra_moneda`, `plan_medidas`, `activo`) VALUES 
('1', '1', '1', '2', '1', '15', '1;2;5', '15', '15', '15', '15', '15', '15', '2020-04-16', '2020-04-30', '2020-04-14', '2020-04-28', 'CAA', '12.45', NULL, '15.45', '1', '1'),
('2', '12', '3', '6', '2', '17', '1;2;5', '12', '12', '24', '36', '2', '3', '2020-04-06', '2020-04-20', '2020-04-09', '2020-04-23', 'CAA', '12.45', '48.2', '89.45', '0', '1');

            
-- 
-- Vaciado de tabla 'auditor'
-- 
DROP TABLE IF EXISTS `auditor`;
                        
--
-- Estructura de tabla para la tabla 'auditor'
--

CREATE TABLE `auditor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidad_id` int(11) NOT NULL,
  `entidad_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `nivel_id` int(11) NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `nombres` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fea` tinyint(1) NOT NULL,
  `rna` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_rna` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CE48CAAD3B67F367` (`ci`),
  UNIQUE KEY `UNIQ_CE48CAAD69FF72CB` (`rna`),
  KEY `IDX_CE48CAAD67707C89` (`localidad_id`),
  KEY `IDX_CE48CAAD6CA204EF` (`entidad_id`),
  KEY `IDX_CE48CAAD813AC380` (`cargo_id`),
  KEY `IDX_CE48CAADDA3426AE` (`nivel_id`),
  CONSTRAINT `FK_CE48CAAD67707C89` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`),
  CONSTRAINT `FK_CE48CAAD6CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`),
  CONSTRAINT `FK_CE48CAAD813AC380` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`id`),
  CONSTRAINT `FK_CE48CAADDA3426AE` FOREIGN KEY (`nivel_id`) REFERENCES `nivel` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=567 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'auditor'
--

INSERT INTO `auditor` (`id`, `localidad_id`, `entidad_id`, `cargo_id`, `nivel_id`, `imagen`, `activo`, `nombres`, `apellidos`, `ci`, `direccion`, `telefono`, `correo`, `fea`, `rna`, `fecha_rna`) VALUES 
('2', '1', '1', '1', '16', '', '1', 'Carmen Elsa', 'Alfonso Oceguera', '64122300472', 's/d', 's/n', 'ejemplo@correo.cu', '0', '42', '2014-10-24'),
('3', '1', '1', '2', '16', '715a2770b03ffc7c63d865f234cd0f67.jpeg', '1', 'Liliana', 'Chacón Galván', '65091601973', 's/d', '284293', 'ejemplo@correo.cu', '0', '47', '2014-10-24'),
('4', '1', '1', '3', '17', '', '1', 'Carlos', 'de Armas Morales', '64082341884', 's/d', 's/n', 'ejemplo@correo.cu', '0', '43', '2014-10-24'),
('5', '1', '1', '3', '21', '363812e491d09029247eebbf32432a62.jpeg', '1', 'Olivia', 'Alfonso Peñate', '62120113951', 's/d', '280693', 'ejemplo@correo.cu', '0', '44', '2018-06-25'),
('6', '4', '1', '4', '43', '', '1', 'Alicia Niurka', 'Alfonso Angulo', '60100600354', 's/d', '52333420', 'ejemplo@correo.cu', '0', '128', '2014-08-12'),
('8', '1', '1', '2', '9', '', '1', 'Nancy', 'Reyes Ramírez', '58100909794', 's/d', '269233', 'ejemplo@correo.cu', '0', '45', '2013-10-24'),
('9', '1', '1', '5', '17', '', '1', 'María Teresa', 'Blanco García', '64030214235', 's/d', '282152', 'ejemplo@correo.cu', '0', '7335', '2014-04-15'),
('10', '1', '1', '5', '17', '', '1', 'Yahimara', 'Bazán Rodríguez', '81092908190', 's/d', 's/n', 'ejemplo@correo.cu', '1', '9617', '2013-04-17'),
('11', '1', '1', '6', '31', '', '1', 'Laureano Jesús', 'López García', '45032000724', 's/d', '242893', 'ejemplo@correo.cu', '0', '1262', '2013-08-27'),
('12', '1', '1', '6', '48', '', '1', 'Ramón', 'Lorenzo González', '61090522324', 's/d', '45445445', 'ejemplo@correo.cu', '0', '4352', '2013-10-07'),
('13', '15', '1', '5', '17', '', '1', 'Nancy', 'Santana  Santana', '63112813732', 's/d', '237326', 'ejemplo@correo.cu', '0', '7251', '2014-04-15'),
('14', '1', '1', '6', '49', '', '1', 'Anisley', 'Gómez Pérez', '84061009211', 's/d', '291095', 'ejemplo@correo.cu', '0', '10049', '2013-07-08'),
('15', '1', '1', '9', '17', '', '1', 'Anailén', 'Alvarez Martínez', '89012527217', 's/d', '288503', 'ejemplo@correo.cu', '1', '11136', '2018-06-25'),
('16', '1', '1', '8', '17', '', '1', 'Wina', 'Baró Guerrero', '83041307056', 's/d', '260937', 'ejemplo@correo.cu', '1', '9702', '2013-07-23'),
('17', '27', '1', '9', '17', '', '1', 'Yurisel', 'Gómez Sablón', '83100502012', 's/d', 's/n', 'ejemplo@correo.cu', '1', '11869', '2015-02-24'),
('18', '1', '1', '7', '2', '', '1', 'Marialis', 'Montes de Oca Gil', '90110429534', 's/d', 's/n', 'ejemplo@correo.cu', '1', '13723', '2016-06-30'),
('20', '1', '1', '9', '15', '', '1', 'Liuba', 'de la Caridad Reina Alfonso', '85090809215', 's/d', '263937', 'ejemplo@correo.cu', '0', '13452', '2011-04-26'),
('21', '1', '1', '9', '5', '', '1', 'Candelario', 'Lorenzo Martinez Rodríguez', '55020201061', 's/d', 's/n', 'ejemplo@correo.cu', '0', '14063', '2016-08-09'),
('23', '1', '1', '8', '14', '', '1', 'Julia', 'Coralia  Acosta Beltrán', '63100618752', 's/d', '282341', 'ejemplo@correo.cu', '0', '11820', '2015-04-14'),
('24', '1', '1', '10', '50', '', '1', 'Carmen', 'Hernández Albelo', '62051400990', 's/d', 's/n', 'ejemplo@correo.cu', '0', '11931', '2015-02-18'),
('25', '1', '1', '9', '17', '', '1', 'Maria', 'Hurtado Mendoza', '87120811372', 's/d', 's/n', 'ejemplo@correo.cu', '0', '13592', '2016-08-02'),
('27', '1', '1', '10', '34', '', '1', 'Omaida', 'Mesa Ledo', '61031512736', 's/d', '295881', 'ejemplo@correo.cu', '0', '16730', '2014-03-03'),
('28', '40', '1', '7', '2', '', '1', 'Isel', 'Tápanes Bolaños', '90030125214', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17891', '2015-07-02'),
('29', '1', '1', '21', '18', '', '1', 'Raúl', 'Castellanos Amador', '58041800843', 's/d', '281777', 'ejemplo@correo.cu', '0', '18345', '2016-02-19'),
('30', '6', '1', '9', '15', '', '1', 'Nuria', 'de la Caridad Ministral Aispurua', '69073000175', 's/d', '45814868', 'ejemplo@correo.cu', '0', '6306', '2016-08-31'),
('31', '1', '1', '9', '16', '', '1', 'Zulai', 'Ibargollón Perez', '86021709293', 's/d', '266917', 'ejemplo@correo.cu', '0', '17967', '2015-07-31'),
('32', '1', '1', '9', '6', '', '1', 'Miriam', 'Falcón Suarez', '61051221656', 's/d', '273111', 'ejemplo@correo.cu', '0', '17966', '2015-07-31'),
('33', '4', '1', '6', '2', '', '1', 'Yadanis', 'Dominguez Alvarez', '88021913298', 's/d', 's/n', 'ejemplo@correo.cu', '1', '13772', '2018-06-25'),
('34', '4', '1', '7', '17', '', '1', 'Dianelis', 'Esquijarrosa Allui', '88052313138', 's/d', '52333419', 'ejemplo@correo.cu', '1', '13593', '2018-06-25'),
('35', '4', '1', '5', '17', '', '1', 'Yenisel', 'Nuñez Jerez', '82080728818', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9915', '2013-08-27'),
('36', '4', '1', '9', '12', '', '1', 'Lainer', 'Li Pere', '84081112505', 's/d', '54251646', 'ejemplo@correo.cu', '0', '13033', '2018-06-25'),
('37', '4', '1', '7', '3', '', '1', 'Yanelis', 'Figueroa Perez', '88101313055', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17636', '2018-06-25'),
('38', '4', '1', '7', '17', '', '1', 'Marileydis', 'Torres Hernández', '89011125274', 's/d', 's/n', 'ejemplo@correo.cu', '1', '11132', '2014-06-24'),
('39', '1', '2', '11', '3', '', '1', 'Francisca', 'Socorro Pérez Fernández', '49062700871', 's/d', '265546', 'ejemplo@correo.cu', '0', '9508', '2013-12-01'),
('40', '1', '2', '12', '15', '', '1', 'Elsa', 'María Mata Alcina', '63053020371', 's/d', 's/n', 'ejemplo@correo.cu', '0', '7368', '2001-06-24'),
('42', '1', '2', '13', '48', '', '1', 'Yailen', 'Jorrin Diaz', '78080312859', '348 No 10318 A entre 103 y 107 Naranjal Norte', 's/n', 'ejemplo@correo.cu', '0', '15116', '2013-01-18'),
('43', '1', '2', '13', '15', '', '1', 'Aridna', 'María Borges Borrego', '78011611896', 's/d', '52953570', 'ejemplo@correo.cu', '0', '18071', '2015-10-20'),
('45', '1', '2', '15', '3', '', '1', 'Maritza', 'Santana Monzón', '63111914179', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16046', '2013-10-06'),
('46', '1', '2', '14', '16', '', '1', 'Julio', 'Acosta Batista', '60051512389', 's/d', 's/n', 'ejemplo@correo.cu', '0', '7884', '2015-08-17'),
('47', '1', '4', '15', '15', '', '1', 'Israel', 'Martell Segismundo', '39090600722', 's/d', '291372', 'ejemplo@correo.cu', '0', '12831', '2015-10-20'),
('48', '13', '2', '13', '15', '', '1', 'Barbara', 'Daniela Delgado Rossel', '87120413207', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15989', '2013-05-23'),
('49', '39', '5', '13', '1', '', '1', 'Vilma', 'Suarez Castillo', '64072518934', 's/d', 's/n', 'ejemplo@correo.cu', '0', '2000', '2011-03-15'),
('50', '2', '6', '13', '16', '', '1', 'Ángel', 'Modesto Hernández Morales', '49090809308', 's/d', '610469', 'ejemplo@correo.cu', '0', '6367', '2012-09-10'),
('52', '39', '8', '17', '16', '', '1', 'Olga', 'Lidia Sotero Aguilera', '63080316575', 's/d', '52798427', 'ejemplo@correo.cu', '0', '15196', '2012-04-10'),
('53', '2', '143', '13', '16', '', '1', 'Yoslaine', 'Pérez Pajarin', '76010509632', 's/d', '43-569275', 'ejemplo@correo.cu', '0', '6945', '2014-01-30'),
('54', '2', '9', '13', '15', '', '1', 'Josefa', 'Villalón Saukle', '75040242839', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16823', '2014-04-15'),
('55', '1', '10', '14', '3', '', '1', 'Yureinis', 'Enriquez Tabío', '92030831156', 's/d', '263696', 'ejemplo@correo.cu', '0', '15622', '2013-02-12'),
('56', '1', '10', '14', '3', '', '1', 'Magnolia', 'de la Rosa Romero', '91051207779', 's/d', '58348450', 'ejemplo@correo.cu', '0', '15986', '2013-05-23'),
('57', '1', '11', '16', '30', '', '1', 'Idorys', 'Martínez Díaz', '61010100875', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15680', '2013-03-04'),
('59', '1', '12', '14', '1', '', '1', 'Francisco', 'Terras García', '55010400663', 's/d', '45454545', 'ejemplo@correo.cu', '0', '3342', '1999-11-30'),
('60', '1', '44', '13', '17', '', '1', 'Anabel', 'Navarro Mederos', '87062411531', 's/d', 's/n', 'ejemplo@correo.cu', '1', '10976', '2016-11-16'),
('61', '1', '13', '13', '17', '', '1', 'Silvia', 'Beatriz Fernández González', '87040811375', 's/d', '53362931', 'ejemplo@correo.cu', '1', '11147', '2014-04-30'),
('63', '1', '13', '14', '3', '', '1', 'Aydelis', 'de la C. Alpizar Cast.', '93111734256', 's/d', '52272326', 'ejemplo@correo.cu', '1', '16518', '2013-11-18'),
('65', '6', '13', '17', '5', '', '1', 'Diamildes', 'Almeda Rosales', '77110509072', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15673', '2013-03-04'),
('66', '6', '13', '13', '11', '', '1', 'Maria', 'de la Luz Menendez Mesa', '56051300075', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15987', '2013-05-23'),
('67', '7', '13', '15', '39', '', '1', 'Blanca', 'Nieves Pulgaron Rodriguez', '54061800574', '22 No. 2516 e/ 25 y 27, Pedro Betancourt', '898486', 'ejemplo@correo.cu', '0', '15852', '2013-04-17'),
('68', '6', '13', '15', '3', '', '1', 'Yasnai', 'Sigler Boitel', '71021412699', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16985', '2014-07-10'),
('70', '1', '15', '14', '16', '', '1', 'Ernesto', 'L. Aldazabal Chapell', '64011014464', 's/d', 's/n', 'ejemplo@correo.cu', '0', '3407', '2013-07-23'),
('71', '5', '15', '14', '16', '', '1', 'Josefa', 'Hernández Pérez', '49031900537', 's/d', '377384', 'ejemplo@correo.cu', '0', '6730', '2015-08-10'),
('74', '7', '15', '13', '16', '', '1', 'Arturo', 'J. Fagundo Sotolongo', '51010700148', 's/d', 's/n', 'ejemplo@correo.cu', '0', '2666', '2013-03-04'),
('75', '7', '16', '14', '11', '', '1', 'Ilena', 'Calderón martinez', '65122433095', 's/d', '58674215', 'ejemplo@correo.cu', '0', '18335', '2016-02-17'),
('76', '4', '156', '15', '3', '', '1', 'Dayma', 'Suarez Saavedra', '88091241213', 's/d', 's/n', 'ejemplo@correo.cu', '1', '12849', '2016-01-26'),
('77', '12', '17', '13', '16', '', '1', 'Regla', 'Z. Bravo Aballí', '69051000514', 's/d', '374696', 'ejemplo@correo.cu', '0', '15198', '2012-10-04'),
('78', '8', '18', '17', '16', '', '1', 'Norberto', 'Díaz Pérez', '63092015842', 's/d', '454454', 'ejemplo@correo.cu', '0', '5335', '2011-04-26'),
('79', '8', '19', '14', '16', '', '1', 'Esther', 'de Armas Prince', '57112100852', 's/d', '235179', 'ejemplo@correo.cu', '0', '16736', '2014-03-03'),
('80', '11', '20', '14', '3', '', '1', 'Juan', 'Heriberto Sánchez Lopez', '45071200280', 's/d', 's/n', 'ejemplo@correo.cu', '0', '6072', '2013-01-18'),
('81', '11', '20', '14', '3', '', '1', 'Omaida', 'Reina Perez Castillo', '62020400292', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15482', '2013-01-18'),
('82', '11', '20', '17', '30', '', '1', 'Pedro', 'Diaz Sopeña', '49101900184', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15486', '2013-01-18'),
('83', '11', '20', '14', '3', '', '1', 'Oilda', 'M. Perez Castillo', '66082401771', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15485', '2013-01-18'),
('84', '11', '20', '14', '3', '', '1', 'ErnestinaF.', 'Palledo Ferrin', '53110400836', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15484', '2013-01-18'),
('85', '11', '20', '14', '3', '', '1', 'Sonia', 'Caridad Vera Puentes', '63122401731', '15 C 2419 entre 24 y 26 Torriente', 's/n', 'ejemplo@correo.cu', '0', '15483', '2013-01-18'),
('86', '11', '20', '14', '11', '', '1', 'Amaurys', 'Martín Angulo', '53010700201', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16468', '2013-10-30'),
('87', '11', '20', '14', '28', '', '1', 'Pedro', 'Luis Rodriguez García', '62093000566', '24 de Febrero Casa No 17 entre Moncada e Insolación Agramonte', '4545445', 'ejemplo@correo.cu', '0', '16380', '2013-10-16'),
('88', '4', '21', '15', '17', '', '1', 'Annelis', 'Rodriguez Martinez', '85080509851', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16970', '2014-06-24'),
('90', '1', '22', '15', '3', '', '1', 'Anays', 'Esther Hernández Perdomo', '93111934131', 's/d', 's/n', 'ejemplo@correo.cu', '1', '16540', '2013-11-25'),
('91', '1', '23', '10', '15', '', '1', 'Massiel', 'Ortiz Ramirez', '80032409179', 's/d', '52230048', 'ejemplo@correo.cu', '0', '15142', '2013-01-18'),
('92', '1', '23', '15', '2', '', '1', 'Sury', 'Avila Villalonga', '93020634111', '272 No. 12708 e/ 127 y 131. Pueblo Nuevo. Matanzas.', '52253367', 'ejemplo@correo.cu', '1', '15866', '2013-04-18'),
('93', '1', '23', '15', '17', '', '1', 'Yulieska', 'Sarmiento Hernández', '81111923639', 's/d', 's/n', 'ejemplo@correo.cu', '0', '11463', '2015-11-19'),
('94', '1', '24', '14', '3', '', '1', 'Julio', 'Pérez García', '38052700644', 's/d', 's/n', 'ejemplo@correo.cu', '0', '6865', '2014-08-19'),
('96', '1', '25', '15', '17', '', '1', 'Neylis', 'Carrilo Lago', '91051707779', 's/d', '242940', 'ejemplo@correo.cu', '1', '14930', '2000-00-00'),
('97', '1', '26', '18', '14', '', '1', 'Reynaldo', 'Juan Vela Garcia', '45030800306', 's/d', '253607', 'ejemplo@correo.cu', '0', '15488', '2013-01-18'),
('98', '1', '26', '19', '26', '', '1', 'Jose', 'Manuel Benitez Rodriguez', '62102101841', 's/d', '244361', 'ejemplo@correo.cu', '0', '16885', '2014-05-12'),
('100', '1', '26', '13', '15', '', '1', 'Caridad', 'Pérez Rodríguez', '58110400695', 's/d', '265068', 'ejemplo@correo.cu', '0', '17094', '2014-08-21'),
('101', '1', '26', '13', '15', '', '1', 'Mildred', 'Vega Zaragoza', '88011711212', 's/d', '247456', 'ejemplo@correo.cu', '1', '15674', '2013-03-04'),
('102', '1', '26', '13', '15', '', '1', 'Sandy', 'Sánchez Pérez', '86092609301', 's/d', '267065 casa', 'ejemplo@correo.cu', '1', '13218', '2015-12-16'),
('103', '6', '26', '13', '35', '', '1', 'Alfredo', 'Cárdenas Cabrera', '72110400061', 's/d', '812410', 'ejemplo@correo.cu', '0', '10239', '2013-10-09'),
('104', '1', '26', '19', '16', '', '1', 'José', 'Raimundo Flores Álvarez', '43111800066', 's/d', '290000', 'ejemplo@correo.cu', '0', '3722', '2015-12-14'),
('105', '1', '26', '13', '49', '', '1', 'Tamara', 'Ramirez Brito', '70091600871', 's/d', '52247308', 'ejemplo@correo.cu', '0', '14127', '2016-09-26'),
('107', '1', '27', '12', '33', '', '1', 'Elvira', 'Antonia Bermúdez Armas', '66012505555', 's/d', 's/n', 'ejemplo@correo.cu', '0', '470', '2013-08-26'),
('110', '4', '27', '13', '48', '', '1', 'Yuniarys', 'Rodríguez Vega', '82051709633', 's/d', '454545', 'ejemplo@correo.cu', '1', '8954', '2013-01-18'),
('111', '19', '27', '15', '3', '', '1', 'Yamile', 'Pérez Hernández', '72011812812', 's/d', '45454545', 'ejemplo@correo.cu', '0', '13214', '2011-02-23'),
('112', '9', '27', '13', '48', '', '1', 'Mercedes', 'Segundo Govin', '63092201710', 's/d', '454545', 'ejemplo@correo.cu', '0', '8950', '2013-01-18'),
('113', '9', '27', '13', '48', '', '1', 'Reynaldo', 'González Aguila', '55072605449', 's/d', '412961', 'ejemplo@correo.cu', '0', '8128', '2011-02-23'),
('114', '9', '27', '14', '15', '', '1', 'Yacquelin', 'Álvarez González', '71112000119', 's/d', '454545', 'ejemplo@correo.cu', '0', '15496', '2013-01-18'),
('115', '7', '27', '13', '48', '', '1', 'Yailín', 'Morales Calzadilla', '81110909496', 's/d', '5454545', 'ejemplo@correo.cu', '1', '8953', '2013-01-18'),
('116', '7', '27', '15', '1', '', '1', 'Naydelis', 'Delgado Perera', '88042312732', 's/d', 's/n', 'ejemplo@correo.cu', '1', '12846', '2011-01-19'),
('117', '7', '27', '15', '3', '', '1', 'Vania', 'Hernández Herrera', '88120311054', 's/d', '4545454', 'ejemplo@correo.cu', '1', '12854', '2011-01-19'),
('118', '7', '27', '15', '46', '', '1', 'Anabel', 'María Rodriguez Peña', '64060613574', 's/d', '4545445', 'ejemplo@correo.cu', '0', '17924', '2015-07-21'),
('119', '4', '27', '14', '17', '', '1', 'Liuva', 'Villegas González', '75112506111', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17220', '1999-11-30'),
('120', '6', '27', '14', '28', '', '1', 'Ana', 'Belén Calero Cadevieu', '86102210090', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15621', '2013-02-12'),
('121', '6', '27', '14', '16', '', '1', 'Annia', 'Nieves Leyva', '73070105831', 's/d', '815116', 'ejemplo@correo.cu', '0', '16986', '2014-07-10'),
('123', '13', '27', '15', '3', '', '1', 'Anibal', 'Jimenez Gonzalez', '78050617481', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17909', '2015-07-13'),
('125', '13', '27', '15', '3', '', '1', 'Omar', 'Ortega Pérez', '64110200584', 's/d', '379738', 'ejemplo@correo.cu', '0', '16146', '2013-07-23'),
('126', '13', '27', '14', '10', '', '1', 'Estrella', 'Luques Molina', '57080602411', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17691', '2000-00-00'),
('127', '13', '27', '14', '17', '', '1', 'Miriela', 'Marquez Águlia', '88111813374', '8va No 54 entre Reyes Cabrera  y Linea', '52301882', 'ejemplo@correo.cu', '0', '18293', '1999-11-30'),
('128', '13', '27', '14', '17', '', '1', 'Ismelia', 'M Hernández Vázquez', '57080602412', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17376', '1999-11-30'),
('130', '27', '28', '13', '17', '', '1', 'Yahirelis', 'Gonzalez Sanchez', '84040525773', 's/d', '244516 ext 139', 'ejemplo@correo.cu', '0', '13447', '2016-09-27'),
('133', '1', '28', '13', '21', '', '1', 'Livia Maria', 'Alfonso Peñate', '62120113935', 's/d', 's/n', 'ejemplo@correo.cu', '0', '7642', '2012-03-26'),
('134', '1', '28', '13', '16', '', '1', 'Jorge Antonio', 'Lozano Fuentes', '73010800706', 's/d', '45286778', 'ejemplo@correo.cu', '0', '16734', '2014-03-03'),
('136', '9', '30', '15', '3', '', '1', 'Arisleidy', 'Alonso Medina', '89121427378', 's/d', '454545', 'ejemplo@correo.cu', '0', '17496', '2015-02-05'),
('137', '19', '31', '14', '48', '', '1', 'Iris', 'Carmen Piloto León', '60061412292', 's/d', 's/n', 'ejemplo@correo.cu', '0', '8951', '2013-01-18'),
('138', '6', '32', '14', '16', '', '1', 'Humberto', 'Pedro  Hernández Hernández', '52062800244', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15480', '2013-01-18'),
('140', '4', '33', '15', '15', '', '1', 'Javier', 'Sosa Sánchez', '66091700467', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17986', '2015-08-10'),
('142', '3', '35', '15', '47', '', '1', 'Wilfredo', 'M. Cuesta González', '47101500863', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15851', '2013-04-17'),
('150', '1', '47', '14', '3', '', '1', 'Odalys', 'Delgado Domínguez', '88122711292', 's/d', 's/n', 'ejemplo@correo.cu', '1', '9149', '2013-01-18'),
('151', '1', '47', '14', '2', '', '1', 'Odailys', 'Delgado Domínguez', '88122711373', 's/d', 's/n', 'ejemplo@correo.cu', '1', '9150', '2013-01-18'),
('152', '1', '47', '12', '15', '', '1', 'Zobeida', 'María Rodríguez Gómez', '57111700976', 's/d', '266566', 'ejemplo@correo.cu', '0', '477', '2013-12-02'),
('153', '1', '47', '12', '19', '', '1', 'Demetrio', 'Prieto Coto', '41122200408', 's/d', '292693', 'ejemplo@correo.cu', '0', '7424', '2014-05-12'),
('154', '1', '47', '13', '3', '', '1', 'Elia', 'Tamara Gomez', '48021600615', 's/d', '291311', 'ejemplo@correo.cu', '0', '9620', '2013-05-23'),
('155', '1', '47', '12', '16', '', '1', 'Eneida', 'Lazara Martínez Cabrera', '60110210099', 's/d', '45291480', 'ejemplo@correo.cu', '0', '7842', '2011-02-23'),
('156', '1', '47', '12', '15', '', '1', 'Estela', 'Arroyo Fernández', '51110120065', 's/d', '263415', 'ejemplo@correo.cu', '0', '9127', '2013-03-04'),
('157', '1', '47', '12', '16', '', '1', 'Nurys', 'Garcia Caraballo', '70022700931', 's/d', '244702', 'ejemplo@correo.cu', '0', '7721', '2015-07-13'),
('158', '1', '47', '12', '15', '', '1', 'Joyce', 'Vera Martínez', '72033121619', 's/d', '261535', 'ejemplo@correo.cu', '0', '7954', '2013-03-04'),
('159', '1', '47', '13', '16', '', '1', 'Eva', 'Josefa Leyva Diaz', '54062300739', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15079', '2013-01-18'),
('160', '1', '47', '13', '3', '', '1', 'Maria', 'Consuelo Suarez Mallada', '66041213711', 's/d', '262414', 'ejemplo@correo.cu', '0', '12304', '2013-03-04'),
('164', '1', '47', '12', '17', '', '1', 'Anabel', 'Trujillo Benítez', '73011400216', 's/d', 's/n', 'ejemplo@correo.cu', '0', '10172', '2013-10-08'),
('165', '1', '47', '12', '17', '', '1', 'Barbara', 'Leonor Valdez Oliva', '56120400916', 's/d', '45454545', 'ejemplo@correo.cu', '0', '3906', '2014-03-23'),
('166', '2', '47', '12', '19', '', '1', 'Servilio', 'Juan Diaz Alonso', '44052400149', 's/d', '528135', 'ejemplo@correo.cu', '0', '7246', '2015-03-30'),
('172', '1', '49', '29', '11', '', '1', 'Teresa', 'Martinez Lorenzo', '70042400399', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17374', '2014-12-18'),
('173', '1', '49', '13', '17', '', '1', 'José', 'Exlen Carriera Díaz', '67112801503', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12194', '2015-03-19'),
('174', '1', '49', '15', '17', '', '1', 'María', 'Griselda Toledo Otero', '61070713117', 's/d', '45294065', 'ejemplo@correo.cu', '0', '12189', '2015-03-19'),
('176', '1', '49', '14', '3', '', '1', 'Jenny', 'Corcho Galera', '88120311055', 's/d', '265690', 'ejemplo@correo.cu', '1', '12283', '2015-03-19'),
('177', '1', '49', '15', '15', '', '1', 'Tania', 'Cruz Piferrer', '68100300995', 's/d', '287862', 'ejemplo@correo.cu', '0', '12835', '2015-12-16'),
('178', '1', '49', '14', '3', '', '1', 'Mirta', 'Berriel Domenech', '64081523132', 's/d', '287862', 'ejemplo@correo.cu', '0', '12836', '2015-12-16'),
('180', '1', '49', '14', '7', '', '1', 'Tahimi', 'Ramirez García', '87111811399', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15481', '2013-01-18'),
('181', '1', '49', '13', '22', '', '1', 'Humberto', 'González Fiallo', '65021001226', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15671', '2013-07-01'),
('182', '1', '49', '14', '15', '', '1', 'Leidy', 'Fuentes Perez', '86062912017', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18022', '2015-09-18'),
('183', '1', '49', '14', '17', '', '1', 'Beatriz', 'Martorell Almagro', '88110511212', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17826', '2015-06-09'),
('186', '1', '49', '15', '3', '', '1', 'Arlety', 'Hernandez Peña', '95071331455', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18338', '2016-02-17'),
('187', '8', '49', '14', '25', '', '1', 'Ariel', 'González Fernandez', '58100779002', 's/d', '235676', 'ejemplo@correo.cu', '0', '16825', '2014-04-15'),
('189', '35', '54', '15', '3', '', '1', 'Mildrei', 'Rizo vasconcelos', '81110309612', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16881', '2014-05-12'),
('190', '2', '55', '14', '17', '', '1', 'Juan Antonio', 'Fernández Méndez', '59071902763', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17846', '2015-06-26'),
('191', '5', '56', '15', '3', '', '1', 'Isora', 'Sosa Garcia', '66082300454', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12834', '2015-12-16'),
('192', '17', '57', '14', '15', '', '1', 'Yudiet', 'Perdomo Chavez', '83051608496', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16880', '2014-05-12'),
('193', '11', '58', '14', '15', '', '1', 'Rafael', 'Yandro Sosa Vázquez', '83121921629', '7-A s/n entre 80-8 y final', 's/n', 'ejemplo@correo.cu', '1', '12284', '2015-03-19'),
('194', '6', '59', '14', '15', '', '1', 'Maira', 'Pena Rivera', '57022700954', 's/d', 's/n', 'ejemplo@correo.cu', '0', '6951', '2015-12-16'),
('196', '7', '61', '14', '15', '', '1', 'Enisbet', 'Medina Cruz', '70022313132', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12195', '2015-03-19'),
('197', '12', '62', '14', '15', '', '1', 'Damaris', 'Daniel Ruiz', '72050300234', 's/d', '286572', 'ejemplo@correo.cu', '0', '12852', '2015-12-16'),
('198', '13', '63', '14', '15', '', '1', 'Yahomi', 'Calderón Lorenzo', '80102209574', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15856', '2013-06-01'),
('199', '3', '65', '14', '15', '', '1', 'Lucia', 'Caridad Sánchez Guitar', '78022612376', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12186', '2015-03-19'),
('201', '1', '51', '13', '21', '', '1', 'Iván Jorge', 'Bonilla Rodríguez', '71020715468', 's/d', '45263679', 'ejemplo@correo.cu', '0', '9224', '2013-03-04'),
('202', '1', '51', '13', '24', '', '1', 'Luis Miguel', 'Castillo Acosta', '63101101326', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9576', '2013-04-17'),
('203', '1', '51', '13', '28', '', '1', 'Mercedes', 'Vega Héctor', '61092402172', 's/d', '45263177', 'ejemplo@correo.cu', '0', '6976', '2014-05-12'),
('204', '1', '51', '15', '15', '', '1', 'Asahay', 'Ballester García', '87092711377', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9239', '2013-03-04'),
('205', '1', '51', '15', '3', '', '1', 'Milagros', 'de la Caridad García Villalonga', '63072001174', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12213', '2015-02-24'),
('206', '1', '51', '15', '3', '', '1', 'Diancy', 'Hernández Fernández', '90090129692', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12209', '2015-02-24'),
('207', '1', '51', '14', '3', '', '1', 'Niurka', 'Marrero Pérez', '61111200137', 's/d', '243670', 'ejemplo@correo.cu', '0', '12210', '2015-02-24'),
('208', '1', '51', '29', '3', '', '1', 'Vivian', 'Prado Alonso', '62080600412', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9238', '2013-03-04'),
('209', '1', '51', '15', '2', '', '1', 'Fabianne', 'Díaz Ayala', '56012000094', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15855', '2013-04-18'),
('210', '1', '51', '15', '2', '', '1', 'Evelyn', 'Hernández Santos', '87060211375', '121, No. 27408, e/ 274 y 276, Pueblo Nuevo, Matanzas', '45293270', 'ejemplo@correo.cu', '1', '10244', '2013-04-17'),
('211', '1', '51', '13', '15', '', '1', 'Maria', 'de la Caridad Torres Escalona', '61012814713', '99 No. 30013 e/ 300 y 302. Matanzas', 's/n', 'ejemplo@correo.cu', '0', '15854', '2013-04-17'),
('213', '1', '51', '14', '10', '', '1', 'Mercedes', 'Calderín González', '57092201051', 's/d', '45262681', 'ejemplo@correo.cu', '0', '9231', '2013-03-04'),
('214', '1', '51', '15', '15', '', '1', 'Beatriz', 'Gil Coruña', '79081626496', 's/d', '45235815', 'ejemplo@correo.cu', '0', '9229', '2013-03-04'),
('215', '1', '51', '14', '15', '', '1', 'Juan', 'Carlos Lago Leiva', '74041935800', 's/d', 's/n', 'ejemplo@correo.cu', '0', '8429', '2016-09-27'),
('217', '1', '49', '15', '17', '', '1', 'Lianet', 'Gómez Morera', '90090529957', 's/d', '263492', 'ejemplo@correo.cu', '1', '14817', '2015-03-19'),
('218', '1', '51', '14', '10', '', '1', 'Angela del Rosario', 'Ochoa Rondón', '66092708214', 's/d', '45269251', 'ejemplo@correo.cu', '0', '18072', '2015-10-20'),
('219', '2', '75', '15', '3', '', '1', 'María Josefa', 'Martínez Montano', '64052100290', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9236', '2013-03-04'),
('220', '7', '76', '14', '32', '', '1', 'Martha', 'Chirino García', '57042100132', 's/d', '45898403', 'ejemplo@correo.cu', '0', '2817', '2016-07-20'),
('221', '9', '77', '15', '15', '', '1', 'Mariela', 'Rodríguez Márquez', '68051201099', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9235', '2013-03-04'),
('223', '12', '78', '15', '2', '', '1', 'Nelson', 'Rodríguez Lugones', '72110303042', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9573', '2013-04-17'),
('224', '3', '79', '15', '15', '', '1', 'Maribel', 'Fundora Díaz', '65052813850', 's/d', '569224', 'ejemplo@correo.cu', '0', '9225', '2013-03-04'),
('225', '8', '80', '14', '15', '', '1', 'Martha', 'Caballero de Armas', '59013011590', 's/d', '45235815', 'ejemplo@correo.cu', '0', '9227', '2013-03-04'),
('226', '10', '86', '15', '21', '', '1', 'Maritza', 'Sotolongo Acosta', '66042400450', 's/d', '45290130', 'ejemplo@correo.cu', '0', '9228', '2013-03-04'),
('227', '11', '87', '15', '3', '', '1', 'Gisela', 'Troya Luzbet', '64121000298', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9237', '2013-03-04'),
('228', '13', '88', '15', '3', '', '1', 'María', 'de los Ángeles González Delgado', '48041400253', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9233', '2013-03-04'),
('229', '6', '91', '14', '15', '', '1', 'Carmen', 'Candelaria Armas Armas', '74011205877', 's/d', 's/n', 'ejemplo@correo.cu', '0', '9226', '2013-03-04'),
('231', '1', '92', '13', '15', '', '1', 'Antje', 'Hernández Fraga', '83072406972', 's/d', '45262035', 'ejemplo@correo.cu', '1', '10570', '2014-03-03'),
('233', '1', '48', '19', '33', '', '1', 'Aliuska', 'Macías Suarez', '67122900113', 's/d', '266633', 'ejemplo@correo.cu', '0', '6601', '1999-11-30'),
('234', '1', '48', '14', '4', '', '1', 'Migdalia', 'Silva Alfonso', '39121401013', 's/d', 's/n', 'ejemplo@correo.cu', '0', '6187', '2013-12-18'),
('235', '1', '48', '28', '15', '', '1', 'Carmen', 'Rosa Almodovar Rodriguez', '67040401077', 's/d', 's/n', 'ejemplo@correo.cu', '0', '13222', '2011-02-23'),
('236', '1', '48', '28', '16', '', '1', 'Aydelin', 'Castillo Montero', '88090211215', 's/d', '292277', 'ejemplo@correo.cu', '0', '15492', '2013-01-18'),
('237', '1', '48', '28', '16', '', '1', 'Ismaray', 'Riveron Pelier', '87112626894', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16599', '2013-12-23'),
('238', '1', '48', '28', '33', '', '1', 'Nevaldo', 'García Castellanos', '67100901622', 's/d', '242135', 'ejemplo@correo.cu', '0', '7255', '2014-03-03'),
('240', '1', '48', '28', '3', '', '1', 'Claudia', 'Martha Vega Hernandez', '89120127454', 's/d', '287408', 'ejemplo@correo.cu', '0', '18210', '2015-12-16'),
('241', '7', '48', '28', '3', '', '1', 'Danay', 'Cruz Gutierrez', '88061311135', 's/d', 's/n', 'ejemplo@correo.cu', '1', '11135', '2012-04-12'),
('242', '6', '48', '28', '15', '', '1', 'Rosa', 'María Alfonso Oviedo', '60011402831', 's/d', 's/n', 'ejemplo@correo.cu', '0', '4969', '2011-04-26'),
('243', '6', '48', '28', '16', '', '1', 'Rodobaldo', 'Hernández López', '62081225728', 's/d', '45814577', 'ejemplo@correo.cu', '0', '6554', '2013-03-04'),
('244', '23', '48', '28', '3', '', '1', 'Danaisis', 'Marrero Alfonso', '88122611052', 's/d', '419216', 'ejemplo@correo.cu', '1', '10695', '2014-03-01'),
('245', '1', '48', '28', '2', '', '1', 'Adrian', 'Pita Aballi', '90082729789', 's/d', 's/n', 'ejemplo@correo.cu', '1', '14824', '2017-02-23'),
('246', '4', '48', '28', '48', '', '1', 'Irma', 'Hernández Sardiñas', '54050700270', 's/d', 's/n', 'ejemplo@correo.cu', '0', '3184', '1999-11-30'),
('248', '1', '48', '15', '3', '', '1', 'Anabel de la Caridad', 'Alonso Ojeda', '92090331378', 's/d', '45289074', 'ejemplo@correo.cu', '0', '15620', '2013-01-12'),
('250', '2', '52', '14', '16', '', '1', 'Annerys', 'Anisia Díaz Olivera', '61103100815', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15493', '2013-01-18'),
('255', '2', '122', '34', '15', '', '1', 'Yudalys', 'del Sol Cabañin', '70091501033', 's/d', '45523926 casa', 'ejemplo@correo.cu', '0', '13454', '2011-04-26'),
('257', '39', '160', '13', '15', '', '1', 'Israel', 'Sanabria Garcia', '52021601160', 's/d', 's/n', 'ejemplo@correo.cu', '0', '13216', '2011-02-23'),
('258', '16', '140', '14', '19', '', '1', 'Agustín', 'Pedro Soler del Portal', '52101901169', 's/d', '45613411', 'ejemplo@correo.cu', '0', '15678', '2013-03-04'),
('261', '1', '130', '13', '16', '', '1', 'Jesús', 'González Rodríguez', '72051912149', 's/d', '45290224', 'ejemplo@correo.cu', '0', '14230', '2011-12-09'),
('262', '3', '127', '14', '27', '', '1', 'Rosa F.', 'García Miranda', '66081414111', 's/d', '45667013 ext8247', 'ejemplo@correo.cu', '0', '7948', '1999-11-30'),
('264', '2', '121', '34', '15', '', '1', 'José', 'Vázquez Fabre', '67122519629', 's/d', '45617730', 'ejemplo@correo.cu', '0', '8405', '2011-12-02'),
('265', '1', '96', '13', '29', '', '1', 'Diana Rosa', 'Palacio García', '66060801311', 's/d', '45285708', 'ejemplo@correo.cu', '0', '17377', '2014-12-18'),
('266', '2', '121', '13', '15', '', '1', 'Ana Luisa', 'Saavedra Reynaldo', '57091300179', 's/d', '45611816', 'ejemplo@correo.cu', '0', '15868', '2013-04-18'),
('267', '2', '96', '13', '3', '', '1', 'Dayris', 'García Quian', '90080730433', 's/d', 's/n', 'ejemplo@correo.cu', '1', '14228', '2011-12-09'),
('270', '2', '121', '14', '15', '', '1', 'Maykel', 'Piloto Izquierdo', '85052909542', 's/d', 's/n', 'ejemplo@correo.cu', '0', '13446', '2011-04-26'),
('272', '1', '139', '13', '16', '', '1', 'Bárbaro Samuel', 'Cruz Valido', '67072801449', 's/d', '54556214', 'ejemplo@correo.cu', '0', '4207', '2016-09-26'),
('273', '1', '129', '14', '15', '', '1', 'Miriam Gertrudis', 'Enriquez Fernández', '58111700810', 's/d', '45288110', 'ejemplo@correo.cu', '0', '4564', '2015-12-16'),
('274', '1', '133', '13', '16', '', '1', 'Ernesto', 'Suarez Herrera', '58111611362', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16516', '2013-11-18'),
('275', '1', '126', '13', '16', '', '1', 'Bertha Elena', 'López Moreda', '68011800853', 's/d', '45247420', 'ejemplo@correo.cu', '0', '15491', '2013-01-18'),
('276', '1', '119', '13', '16', '', '1', 'Elga', 'de la Caridad Granda Santiesteban', '55101201118', 's/d', '45261212', 'ejemplo@correo.cu', '0', '18089', '2015-10-30'),
('277', '1', '121', '13', '15', '', '1', 'Ibrahin', 'Díaz Medina', '75021005587', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16148', '2013-07-23'),
('278', '1', '121', '13', '17', '', '1', 'Lianne', 'Navarro Hernandez', '92122331232', 's/d', 's/n', 'ejemplo@correo.cu', '1', '14808', '2012-04-18'),
('279', '1', '138', '13', '48', '', '1', 'Aberto', 'Raúl Lewis Messan', '46050501162', 's/d', '293534', 'ejemplo@correo.cu', '0', '15495', '2013-01-18'),
('281', '2', '132', '14', '17', '', '1', 'Fidel', 'Lafont Perez', '64090501642', 's/d', 's/n', 'ejemplo@correo.cu', '0', '14812', '2000-00-00'),
('282', '2', '133', '13', '18', '', '1', 'Jorge Luis', 'Arencibia Reyes', '57103000786', 's/d', '45619231', 'ejemplo@correo.cu', '0', '17534', '2015-02-18'),
('283', '2', '96', '14', '3', '', '1', 'Leydis', 'Barbara Romani Perez', '88120914331', 's/d', '45667254', 'ejemplo@correo.cu', '0', '16849', '2014-04-30'),
('285', '16', '120', '14', '16', '', '1', 'Lazara Odalys', 'Villalobo Vivero', '64121701470', 's/d', '45612490', 'ejemplo@correo.cu', '0', '11275', '2014-08-01'),
('286', '1', '148', '10', '50', '', '1', 'María de Los Ángeles', 'García Gómez', '63020807969', 's/d', 's/n', 'ejemplo@correo.cu', '0', '15891', '2013-04-19'),
('289', '38', '131', '14', '17', '', '1', 'Armando', 'Labadi Echeverria', '61101911163', 's/d', '45987114', 'ejemplo@correo.cu', '0', '7620', '2011-01-19'),
('290', '11', '131', '15', '3', '', '1', 'Jose', 'M. Mendez Fernandez', '44090201204', 's/d', '45987212', 'ejemplo@correo.cu', '0', '16378', '2013-10-16'),
('294', '1', '128', '13', '25', '', '1', 'Roberto Alexis', 'Santos Romero', '60101201242', 's/d', '45244695', 'ejemplo@correo.cu', '0', '3344', '2014-05-12'),
('296', '1', '128', '13', '16', '', '1', 'Zeyda Olga', 'Hernández Delgado', '64013101673', 's/d', 's/n', 'ejemplo@correo.cu', '0', '2039', '2015-02-05'),
('298', '1', '123', '14', '16', '', '1', 'Nancy', 'Mirabal González', '69092801433', 's/d', '45269816', 'ejemplo@correo.cu', '0', '7249', '2015-07-13'),
('299', '1', '123', '14', '15', '', '1', 'Arlen', 'Macías Díaz', '81061409139', 's/d', '45260690', 'ejemplo@correo.cu', '1', '9618', '2013-03-04'),
('300', '1', '123', '14', '15', '', '1', 'Yulena', 'Echeverria Rodriguez', '80111801579', 's/d', 's/n', 'ejemplo@correo.cu', '0', '10263', '2013-11-25'),
('302', '16', '123', '14', '17', '', '1', 'María', 'de Los Ángeles Saura Díaz', '63010113999', 's/d', '45611731', 'ejemplo@correo.cu', '0', '3340', '2014-12-24'),
('304', '2', '134', '15', '38', '', '1', 'Valia', 'Marina Rondon Barcenas', '63103114113', 's/d', '45521703', 'ejemplo@correo.cu', '0', '16112', '2013-07-08'),
('306', '1', '67', '14', '16', '', '1', 'Jorge', 'Ruano Walwyn', '80021905984', 's/d', '289031', 'ejemplo@correo.cu', '0', '12851', '2011-01-19'),
('308', '1', '68', '14', '46', '', '1', 'Angel', 'Francisco Millares Domínguez', '51080210082', 's/d', '253155-56 w, 267781c', 'ejemplo@correo.cu', '0', '10507', '2013-10-30'),
('312', '1', '50', '14', '17', '', '1', 'Damaris', 'Caridad Guerra Ayllón', '83090907219', 's/d', '295418', 'ejemplo@correo.cu', '0', '11821', '2015-02-18'),
('313', '1', '50', '12', '16', '', '1', 'Mileysis', 'Lorenzo Lima', '76093005536', 's/d', '293622', 'ejemplo@correo.cu', '0', '8918', '2012-09-07'),
('314', '1', '50', '14', '17', '', '1', 'Daymaris', 'Almeida Delgado', '76120806370', 's/d', '285797', 'ejemplo@correo.cu', '0', '14814', '2013-09-18'),
('316', '9', '71', '14', '17', '', '1', 'Santa', 'Maylen Mesa Piedra', '70032622795', 's/d', 's/n', 'ejemplo@correo.cu', '0', '14931', '2000-00-00'),
('321', '1', '94', '14', '15', '', '1', 'Yarima', 'Ortiz Botino', '84032709371', 's/d', '45256750', 'ejemplo@correo.cu', '0', '16988', '2014-07-10'),
('323', '1', '98', '31', '2', '', '1', 'Regla de la Caridad', 'Leicea Cruz', '60042900951', 's/d', '45260000', 'ejemplo@correo.cu', '0', '3839', '2000-00-00'),
('325', '1', '98', '14', '3', '', '1', 'Marisleydis', 'Salazar  Suarez', '89022127297', 's/d', 's/n', 'ejemplo@correo.cu', '1', '15531', '2013-01-18'),
('327', '1', '99', '14', '2', '', '1', 'Rolando', 'Marrero García', '59032011509', 's/d', '295677 casa', 'ejemplo@correo.cu', '0', '5716', '2011-10-17'),
('329', '1', '100', '14', '3', '', '1', 'María', 'Eugenia Ramos Gómez', '64041830735', 's/d', '267781-casa 253155-5', 'ejemplo@correo.cu', '0', '10008', '2013-07-08'),
('331', '13', '101', '14', '17', '', '1', 'Yunaysi', 'Mercedes Urria Pedroso', '71010722056', 's/d', '45261901', 'ejemplo@correo.cu', '0', '12274', '2015-08-10'),
('332', '13', '101', '14', '15', '', '1', 'Aleida', 'Perez Martinez', '63101627039', 's/d', 's/n', 'ejemplo@correo.cu', '0', '16891', '2014-06-24'),
('334', '5', '101', '15', '2', '', '1', 'Olga Lidia', 'Duquesne Vaquero', '70031300413', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17988', '2015-08-10'),
('335', '8', '102', '14', '28', '', '1', 'Miladis', 'Francés Perdomo', '69073001590', 's/d', '261889 EXT 106-26186', 'ejemplo@correo.cu', '0', '9304', '2013-02-12'),
('336', '6', '103', '15', '2', '', '1', 'Dailin', 'Díaz Macias', '92022532170', 's/d', '45814314', 'ejemplo@correo.cu', '0', '17348', '2014-12-12'),
('337', '6', '137', '14', '17', '', '1', 'Lucy', 'Teresa Bacallao Madán', '70101500092', 's/d', '45813537 casa', 'ejemplo@correo.cu', '0', '18067', '2015-10-20'),
('375', '1', '97', '13', '16', '', '1', 'Santiago Leandro', 'Gorrón Ordex', '44022702383', 's/d', '45265963', 'ejemplo@correo.cu', '0', '13591', '2011-06-02'),
('376', '1', '97', '10', '16', '', '1', 'Ángel Benito', 'Prieto Tocoronte', '37080200661', 's/d', 's/n', 'ejemplo@correo.cu', '0', '12509', '1999-11-30'),
('422', '1', '96', '15', '15', '', '1', 'Lianne', 'Bayol Ferrán', '87120711459', 's/d', '290024', 'ejemplo@correo.cu', '0', '10118', '2013-11-25'),
('448', '1', '64', '15', '3', '', '1', 'Yamilka', 'Cáceres Martínez', '71072800772', 's/d', '245725', 'ejemplo@correo.cu', '0', '12843', '2016-01-19'),
('478', '4', '90', '13', '16', '', '1', 'Maria', 'de Los Ángeles Roca Rosales', '62090207614', 's/d', '45316947', 'ejemplo@correo.cu', '0', '14194', '2016-09-27'),
('483', '1', '2', '14', '15', '', '1', 'Luis A.', 'Cruz Ferrete', '53062207167', 's/d', 's/n', 'ejemplo@correo.cu', '0', '14128', '2011-11-03'),
('494', '1', '1', '13', '1', '', '1', 'Liudmila', 'Diaz Santos', '74103005078', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18593', '2016-05-24'),
('496', '2', '43', '13', '15', '', '1', 'Victor Manuel', 'Hermandar Rodriguez', '68021715589', 's/d', 's/n', 'ejemplo@correo.cu', '2', '18548', '2016-06-09'),
('497', '2', '9', '13', '17', '', '1', 'Mariela', 'Aguero Dorticos', '75092509758', 's/d', '53114329', 'ejemplo@correo.cu', '2', '18630', '2016-06-10'),
('499', '1', '25', '14', '17', '', '1', 'Humberto', 'Cañizares Sánchez', '60112700186', 's/d', 's/n', 'ejemplo@correo.cu', '0', '6401', '2016-06-18'),
('500', '1', '25', '15', '17', '', '1', 'Miriam', 'León Noda', '63030600550', 's/d', 's/n', 'ejemplo@correo.cu', '0', '3511', '2016-04-05'),
('501', '1', '18', '13', '2', '', '1', 'Enervis', 'Fuentes Fonseca', '86091324819', 's/d', 's/n', 'ejemplo@correo.cu', '2', '13442', '2016-04-12'),
('502', '7', '30', '15', '2', '', '1', 'Anaylis', 'Rodriguez Perez', '85122503634', 's/d', 's/n', 'ejemplo@correo.cu', '2', '16241', '2013-08-27'),
('503', '1', '46', '14', '17', '', '1', 'Lisandra', 'Gonzales Ramirez', '87092512658', 's/d', '415384', 'ejemplo@correo.cu', '2', '11134', '2014-04-15'),
('504', '1', '27', '14', '16', '', '1', 'Isabel', 'Cintra  Verdecia', '51122600533', 's/d', '280810', 'ejemplo@correo.cu', '2', '463', '2014-08-19'),
('505', '1', '27', '15', '46', '', '1', 'Jani', 'Hernandez Gonzalez', '72122728996', '9 a casa c2319 entre 28 y 30', 's/n', 'ejemplo@correo.cu', '2', '18479', '2016-04-12'),
('506', '1', '28', '13', '2', '', '1', 'Grisel', 'Guanch Fajardo', '91052507855', 's/d', 's/n', 'ejemplo@correo.cu', '1', '14933', '2010-07-09'),
('507', '1', '156', '15', '2', '', '1', 'Zenia', 'Sainz Peña', '76020305519', 's/d', '45262027', 'ejemplo@correo.cu', '0', '8949', '2013-01-18'),
('508', '1', '45', '13', '2', '', '1', 'Juan José', 'Hernández', '36092700547', 's/d', '292308', 'ejemplo@correo.cu', '0', '16884', '2014-05-12'),
('509', '2', '47', '14', '17', '', '1', 'Yoslaidis', 'del Valle Oramas', '74051305138', 's/d', 's/n', 'ejemplo@correo.cu', '2', '18010', '2015-10-20'),
('510', '2', '47', '14', '15', '', '1', 'Anabel', 'Casañola Vera', '84032509771', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18069', '2015-10-20'),
('511', '1', '49', '15', '17', '', '1', 'Zenia', 'Yera Gil', '77061612518', 's/d', 's/n', 'ejemplo@correo.cu', '2', '14195', '2016-10-28'),
('512', '1', '49', '14', '17', '', '1', 'Liliam', 'Pulin Álvarez', '86080410013', 's/d', '45286572', 'ejemplo@correo.cu', '0', '18663', '2016-06-23'),
('513', '7', '49', '15', '17', '', '1', 'Yamilé', 'Rojas Monroy', '77121009776', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18408', '2016-03-16'),
('514', '9', '49', '15', '17', '', '1', 'Eileen', 'Castro Herrera', '89040327374', 's/d', 's/n', 'ejemplo@correo.cu', '2', '18409', '2016-03-16'),
('515', '21', '60', '15', '15', '', '1', 'Odalys', 'Ramida Rodriguez', '75042410496', 's/d', 's/n', 'ejemplo@correo.cu', '2', '12188', '2016-06-17'),
('516', '4', '49', '15', '1', '', '1', 'Minerva', 'Rivero Ramos', '70051405314', 's/d', 's/n', 'ejemplo@correo.cu', '2', '18627', '2016-06-10'),
('517', '1', '49', '14', '17', '', '1', 'Ratzari', 'Fumero Medina', '74051105059', 's/d', '261442', 'ejemplo@correo.cu', '2', '17599', '2016-02-17'),
('519', '1', '51', '15', '1', '', '1', 'Lisandra', 'González Gutierrez', '92042731231', 's/d', 's/n', 'ejemplo@correo.cu', '0', '1235', '2013-01-18'),
('520', '1', '74', '15', '2', '', '1', 'Lianet', 'Cancio Hernández', '92110631155', 's/d', '52270612', 'ejemplo@correo.cu', '1', '14816', '2017-02-02'),
('522', '2', '51', '15', '17', '', '1', 'Alisleydis Tamara', 'García Morales', '76102507457', 's/d', '45523145', 'ejemplo@correo.cu', '0', '18648', '2016-06-20'),
('523', '40', '48', '28', '16', '', '1', 'Narbelis de la Caridad', 'Faltan los apellidos', '68072701533', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18458', '2016-04-05'),
('524', '1', '48', '28', '2', '', '1', 'Lisandra', 'Gutierrez Aguilar', '86090809298', 's/d', '45263714', 'ejemplo@correo.cu', '2', '18459', '2016-04-05'),
('525', '1', '48', '30', '17', '', '1', 'Cosset', 'Gamón Riaño', '78061012814', 's/d', 's/n', 'ejemplo@correo.cu', '0', '345345', '2016-04-29'),
('526', '1', '48', '31', '17', '', '1', 'Aimee', 'Oramas Gonzalez', '71092601172', 's/d', '45260828', 'ejemplo@correo.cu', '2', '18211', '2015-12-16'),
('527', '1', '94', '14', '17', '', '1', 'Sainé', 'Cortón Avila', '92112224598', 's/d', '45256750', 'ejemplo@correo.cu', '0', '18547', '2016-05-09'),
('528', '1', '98', '14', '2', '', '1', 'Dalia', 'González Montes de Oca', '62071700996', 's/d', '45242856', 'ejemplo@correo.cu', '0', '15672', '2013-03-04'),
('529', '1', '98', '14', '20', '', '1', 'Marina', 'Alvarez Gomez', '58071718076', 's/d', '45245738', 'ejemplo@correo.cu', '2', '7539', '2016-06-20'),
('530', '1', '100', '15', '1', '', '1', 'Moraima', 'Cañizares Ventura', '68021603635', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18433', '2016-03-21'),
('531', '1', '101', '13', '17', '', '1', 'Nestor', 'Hernández Campes', '87012411387', 's/d', '45293404', 'ejemplo@correo.cu', '0', '18628', '2001-11-30'),
('532', '2', '97', '13', '16', '', '1', 'Eduardo Ramón', 'Rodriguez Díaz', '41101304962', 's/d', '45613372', 'ejemplo@correo.cu', '0', '12839', '2016-07-15'),
('533', '1', '128', '19', '16', '', '1', 'Belkis', 'Alfonso Crespo', '65072820592', 's/d', 's/n', 'ejemplo@correo.cu', '2', '', '2016-05-18'),
('534', '1', '73', '15', '1', '', '1', 'Luis', 'Echeverría', '92031331186', 's/d', 's/n', 'ejemplo@correo.cu', '1', '16147', '2013-07-23'),
('535', '1', '122', '15', '1', '', '1', 'Maikel', 'Rodríguez Lorenzo', '79042210525', '374 No.11110-A111 y Calle Campo', '45263044', 'ejemplo@correo.cu', '0', '18629', '2016-06-10'),
('536', '2', '147', '13', '1', '', '1', 'María del Carmen', 'Fiffe Ulloa', '61043012530', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18478', '2016-05-30'),
('538', '2', '150', '14', '10', '', '1', 'Maileydis Hilda', 'Rodríguez Gómez', '74100505833', 's/d', 's/n', 'ejemplo@correo.cu', '0', '17347', '2014-12-12'),
('539', '2', '151', '14', '16', '', '1', 'Anelys', 'Amarante Zenea', '80020808456', 's/d', 's/n', 'ejemplo@correo.cu', '2', '17436', '2014-12-24'),
('540', '1', '153', '13', '16', '', '1', 'Lucila Mercedes', 'Saez Cardenas', '54042000733', 's/d', '45245659', 'ejemplo@correo.cu', '2', '3910', '2016-08-02'),
('541', '1', '95', '14', '16', '', '1', 'Frambiel Wilfredo', 'Gonzalez Vega', '82020711623', 's/d', '45280115', 'ejemplo@correo.cu', '2', '18747', '2016-08-09'),
('542', '1', '123', '14', '2', '', '1', 'Anabel', 'Montejo Rabelo', '93091600711', 's/d', '261457', 'ejemplo@correo.cu', '1', '16098', '2013-07-03'),
('543', '3', '154', '14', '17', '', '1', 'Tamara', 'Padua Vidal', '72022611790', 's/d', '54287931-6-45613012', 'ejemplo@correo.cu', '2', '13224', '2016-12-22'),
('544', '1', '155', '14', '17', '', '1', 'Aymara', 'Rodríguez Zequeiras', '71111013135', 's/d', 's/n', 'ejemplo@correo.cu', '0', '14806', '2010-07-09'),
('545', '1', '155', '13', '25', '', '1', 'Héctor Emilio', 'Núñez Navarro', '57092620700', 's/d', '45244507', 'ejemplo@correo.cu', '0', '3259', '2016-12-22'),
('546', '1', '104', '32', '17', '', '1', 'Arlenis', 'García Súarez', '75110305551', 's/d', '253114', 'ejemplo@correo.cu', '0', '18184', '2015-11-26'),
('547', '1', '104', '14', '17', '', '1', 'Joan', 'Rivera Castellanos', '82120310648', 's/d', 's/n', 'ejemplo@correo.cu', '2', '16289', '2013-01-31'),
('548', '8', '157', '15', '2', '', '1', 'Yaniela', 'García García', '95062031373', 's/d', 's/n', 'ejemplo@correo.cu', '0', '18790', '2016-08-31'),
('549', '1', '27', '12', '33', '', '1', 'Octavio', 'Lorenzo Padrón', '62051801340', 's/d', 's/n', 'ejemplo@correo.cu', '0', '468', '2013-07-03'),
('550', '4', '158', '14', '1', '', '1', 'Yanira', 'Martinez Hernandez', '74040400538', 's/d', 's/n', 'ejemplo@correo.cu', '2', '15846', '2013-04-17'),
('551', '12', '157', '15', '2', '', '1', 'Leydis Barbara', 'Tejedor Martinez', '92042931776', 's/d', 's/n', 'ejemplo@correo.cu', '2', '18876', '2016-10-28'),
('552', '1', '159', '14', '16', '', '1', 'Elina', 'Guerrero Sosa', '79120709591', 's/d', '58682530', 'ejemplo@correo.cu', '0', '18911', '2016-11-16'),
('553', '6', '27', '14', '17', '', '1', 'Yamile', 'Castillo Guerrero', '72030612158', 's/d', '45813243', 'ejemplo@correo.cu', '2', '18910', '2016-11-16'),
('554', '4', '1', '7', '43', '', '1', 'Odalys Francisca', 'Barrios Ceballos', '64100400491', 's/d', '55061293', 'ejemplo@correo.cu', '0', '18930', '2016-12-01'),
('555', '1', '157', '15', '17', '', '1', 'Yordanka', 'Norte Rodriguez', '93081908858', 's/d', '45262545 ext 103', 'ejemplo@correo.cu', '2', '18990', '2016-12-14'),
('556', '1', '157', '15', '17', '', '1', 'Kirenia', 'Cutiñó Hernandez', '87050928653', 's/d', '45262545 ext 103', 'ejemplo@correo.cu', '0', '18991', '2016-12-14'),
('557', '1', '66', '13', '17', '', '1', 'Jorge Alejandro', 'Peralta Venegas', '57042204740', 's/d', '280386', 'ejemplo@correo.cu', '2', '1391', '2014-12-12'),
('558', '1', '161', '13', '16', '', '1', 'Luis Arturo', 'Garc�a de la Torre', '41081902367', '28411', 's/n', 'ejemplo@correo.cu', '0', '45273', '2017-02-10'),
('559', '1', '72', '15', '2', '', '1', 'Leidis', 'Leyva Frómeta', '74030812031', 's/d', '45271475', 'ejemplo@correo.cu', '0', '19116', '2017-02-17'),
('560', '1', '163', '15', '50', '', '1', 'Manuel', 'Alvarez Duany (cachito)', '57103000621', 's/d', '45265387', 'ejemplo@correo.cu', '2', '17526', '2015-02-18'),
('561', '1', '2', '14', '17', '', '1', 'Orelvis', 'Asencio Fleitas', '71120722084', 's/d', 's/n', 'ejemplo@correo.cu', '2', '11473', '2014-12-12'),
('566', '1', '1', '4', '4', 'c51ab7d893520c7b54a7e170191d6081.jpeg', '1', 'Pedro', 'Luis', '96031310507', 'sdfsd', '45260939', 'jkbskac@nauta.cu', '0', '545', '2014-01-01');

            
-- 
-- Vaciado de tabla 'cargo'
-- 
DROP TABLE IF EXISTS `cargo`;
                        
--
-- Estructura de tabla para la tabla 'cargo'
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_contralor` tinyint(1) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3BEE57713A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'cargo'
--

INSERT INTO `cargo` (`id`, `nombre`, `es_contralor`, `activo`) VALUES 
('1', 'Contralora Jefa Provincial', '1', '0'),
('2', 'Contralor Jefe de Departamento', '1', '0'),
('3', 'Vice Contralor Provincial', '1', '0'),
('4', 'Contralor Jefe de Sección', '1', '0'),
('5', 'Auditor Principal de la CGR', '0', '0'),
('6', 'Auditor Supervisor de la CGR', '0', '0'),
('7', 'Auditor Asistente de la CGR', '0', '0'),
('8', 'Auditor Superior de la CGR', '0', '0'),
('9', 'Auditor Adjunto de la CGR', '0', '0'),
('10', 'Auditor Jefe de Grupo', '0', '0'),
('11', 'Auditor Principal Esp. Princ.', '0', '0'),
('12', 'Auditor Principal', '0', '0'),
('13', 'Auditor Adjunto', '0', '0'),
('14', 'Auditor A Asistente', '0', '0'),
('15', 'Auditor B Asistente', '0', '0'),
('16', 'Asistente', '0', '0'),
('17', 'Jefe de Departamento', '0', '0'),
('18', 'Gerente', '0', '0'),
('19', 'Auditor Adjunto Esp. Principal', '0', '0'),
('21', 'Especialista B Jurídico B Auditor CGR', '0', '0'),
('28', 'Auditor Fiscal', '0', '0'),
('29', 'Sub- director Auditoría', '0', '0'),
('30', 'J\' Dpto Fiscalización', '0', '0'),
('31', 'Auditor A Especialista Principal', '0', '0'),
('32', 'Directora UEB', '0', '0'),
('33', 'Oficial de Auditoría', '0', '0'),
('34', 'Auditor Supervisor Especialista principal', '0', '0');

            
-- 
-- Vaciado de tabla 'causa_condicion'
-- 
DROP TABLE IF EXISTS `causa_condicion`;
                        
--
-- Estructura de tabla para la tabla 'causa_condicion'
--

CREATE TABLE `causa_condicion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2E19B9473A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'causa_condicion'
--

INSERT INTO `causa_condicion` (`id`, `nombre`, `activo`) VALUES 
('1', 'Negligencia', '1'),
('2', 'Descuido', '1'),
('3', 'Falta de control', '1'),
('4', 'Falta de supervisi�n', '1');

            
-- 
-- Vaciado de tabla 'combustible'
-- 
DROP TABLE IF EXISTS `combustible`;
                        
--
-- Estructura de tabla para la tabla 'combustible'
--

CREATE TABLE `combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluacion` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dano_economico_cup` double DEFAULT NULL,
  `dano_economico_otra_moneda` double DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'combustible'
--

INSERT INTO `combustible` (`id`, `evaluacion`, `dano_economico_cup`, `dano_economico_otra_moneda`, `activo`) VALUES 
('1', 'A', '12.25', '16.03', '1'),
('2', 'A', '155.64', '160.56', '1');

            
-- 
-- Vaciado de tabla 'entidad'
-- 
DROP TABLE IF EXISTS `entidad`;
                        
--
-- Estructura de tabla para la tabla 'entidad'
--

CREATE TABLE `entidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osde_id` int(11) NOT NULL,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ai` tinyint(1) NOT NULL,
  `nit` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reeup` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uai` tinyint(1) NOT NULL,
  `ucai` tinyint(1) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4587B0CB3A909126` (`nombre`),
  UNIQUE KEY `UNIQ_4587B0CB5E5F5AF3` (`nit`),
  UNIQUE KEY `UNIQ_4587B0CB599B6C7` (`reeup`),
  KEY `IDX_4587B0CB946E5683` (`osde_id`),
  CONSTRAINT `FK_4587B0CB946E5683` FOREIGN KEY (`osde_id`) REFERENCES `osde` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'entidad'
--

INSERT INTO `entidad` (`id`, `osde_id`, `nombre`, `ai`, `nit`, `reeup`, `uai`, `ucai`, `activo`) VALUES 
('1', '1', 'CONTRALOR�A PROVINCIAL', '1', '30100000000', '10000001', '1', '0', '1'),
('2', '2', 'OSDE CONSTRUCCION Y MONTAJE', '1', '10000002', '10000002', '0', '0', '1'),
('3', '2', 'EMPRESA TRANSPORTE', '0', '10000003', '10000003', '0', '0', '1'),
('4', '2', 'MTTO Y CONSTRUCCION', '1', '10000004', '10000004', '0', '0', '1'),
('5', '2', 'EQUIVAR', '0', '10000005', '10000005', '0', '0', '1'),
('6', '2', 'VARSE', '0', '10000006', '10000006', '0', '0', '1'),
('7', '2', 'ESI NO 2', '0', '10000007', '10000007', '0', '0', '1'),
('8', '2', 'ECOING 28', '0', '10000008', '10000008', '0', '0', '1'),
('9', '2', 'ECOA 47', '0', '10000009', '10000009', '0', '0', '1'),
('10', '3', 'LACTEOS', '0', '10000010', '10000010', '0', '0', '1'),
('11', '3', 'MIP', '0', '10000011', '10000011', '0', '0', '1'),
('12', '3', 'CARNICO', '0', '10000012', '10000012', '0', '0', '1'),
('13', '4', 'UAI MINAGRI', '1', '10000013', '10000013', '0', '0', '1'),
('14', '4', 'AGROPECUARIA', '0', '10000014', '10000014', '0', '0', '1'),
('15', '4', 'CAN', '0', '10000015', '10000015', '0', '0', '1'),
('16', '4', 'EMP. ACOPIO Y BENEFICIO DE TABACO', '0', '10000016', '10000016', '0', '0', '1'),
('17', '4', 'Emp. Agroindustrial de Granos Matanzas', '0', '10000017', '10000017', '0', '0', '1'),
('18', '4', 'EMP. GENETICA', '0', '10000018', '10000018', '0', '0', '1'),
('19', '4', 'FRUTAS SELECTAS', '0', '10000019', '10000019', '0', '0', '1'),
('20', '4', 'CITRICOS VICT. DE GIRON', '0', '10000020', '10000020', '0', '0', '1'),
('21', '4', 'Emp. Talleres Agropecuarios Enrique Cabr� Santurio', '0', '10000021', '10000021', '0', '0', '1'),
('22', '4', 'EFI', '0', '10000022', '10000022', '0', '0', '1'),
('23', '4', 'ESTA', '0', '10000023', '10000023', '0', '0', '1'),
('24', '4', 'EMP. DE SILOS', '0', '10000024', '10000024', '0', '0', '1'),
('25', '4', 'EMP. PORCINA', '0', '10000025', '10000025', '0', '0', '1'),
('26', '5', 'AUDITA', '0', '10000026', '10000026', '0', '0', '1'),
('27', '6', 'UCAI-CAP', '0', '10000027', '10000027', '0', '0', '1'),
('28', '6', 'DIRECCION PROV. SALUD', '0', '10000028', '10000028', '0', '0', '1'),
('29', '6', 'EMP. PROV. FARMACIAS Y OPTICAS', '0', '10000029', '10000029', '0', '0', '1'),
('30', '6', 'EDUCACION UNION DE REYES', '0', '10000030', '10000030', '0', '0', '1'),
('31', '6', 'SALUD UNION DE REYES', '0', '10000031', '10000031', '0', '0', '1'),
('32', '6', 'SALUD JOVELLANOS', '0', '10000032', '10000032', '0', '0', '1'),
('33', '6', 'UEB OPTICA', '0', '10000033', '10000033', '0', '0', '1'),
('34', '6', 'UEB FARMACIA COLON', '0', '10000034', '10000034', '0', '0', '1'),
('35', '6', 'SALUD MARTI', '0', '10000035', '10000035', '0', '0', '1'),
('36', '6', 'HOSP. PEDIATRICO ELISEO NOEL CAAMA�O', '0', '10000036', '10000036', '0', '0', '1'),
('37', '6', 'SALUD CARDENAS', '0', '10000037', '10000037', '0', '0', '1'),
('38', '6', 'HOSPITAL PROVINCIAL', '0', '10000038', '10000038', '0', '0', '1'),
('41', '2', 'GECMA', '0', '10000041', '10000041', '0', '0', '1'),
('42', '2', 'ECOA 36', '0', '10000042', '10000042', '0', '0', '1'),
('43', '2', 'Contratista Arcos', '0', '10000043', '10000043', '0', '0', '1'),
('44', '4', 'EDESCOM', '0', '10000044', '10000044', '0', '0', '1'),
('45', '6', 'HOSPITAL FAUSTINO P�REZ', '0', '10000045', '10000045', '0', '0', '1'),
('46', '6', 'Emp. Comercio P. Betanc', '0', '10000046', '10000046', '0', '0', '1'),
('47', '7', 'CANEC', '0', '10000047', '10000047', '0', '0', '1'),
('48', '8', 'ONAT Prov', '0', '10000048', '10000048', '0', '0', '1'),
('49', '8', 'Direcci�n Provincial', '0', '10000049', '10000049', '0', '0', '1'),
('50', '8', 'EISA', '0', '10000050', '10000050', '0', '0', '1'),
('51', '8', 'BANDEC', '0', '10000051', '10000051', '0', '0', '1'),
('52', '8', 'Fondo de Bienes Culturales', '0', '10000052', '10000052', '0', '0', '1'),
('54', '8', 'Sucursal 3602 Maximo Gomez', '0', '10000054', '10000054', '0', '0', '1'),
('55', '8', 'Sucursal 3492', '0', '10000055', '10000055', '0', '0', '1'),
('56', '8', 'Sucursal 3612', '0', '10000056', '10000056', '0', '0', '1'),
('57', '8', '3822 Agramonte', '0', '10000057', '10000057', '0', '0', '1'),
('58', '8', 'Sucursal 3832', '0', '10000058', '10000058', '0', '0', '1'),
('59', '8', 'Sucursal 3652', '0', '10000059', '10000059', '0', '0', '1'),
('60', '8', 'Sucursal 3642', '0', '10000060', '10000060', '0', '0', '1'),
('61', '8', 'Sucursal 3692', '0', '10000061', '10000061', '0', '0', '1'),
('62', '8', 'Sucursal 3852', '0', '10000062', '10000062', '0', '0', '1'),
('63', '8', 'Sucursal 3882', '0', '10000063', '10000063', '0', '0', '1'),
('64', '8', 'Sucursal 3452', '0', '10000064', '10000064', '0', '0', '1'),
('65', '8', 'Sucursal 3532', '0', '10000065', '10000065', '0', '0', '1'),
('66', '8', 'IACC', '0', '10000066', '10000066', '0', '0', '1'),
('67', '8', 'MINDUS-DIVEP', '0', '10000067', '10000067', '0', '0', '1'),
('68', '8', 'SERVINDUS', '0', '10000068', '10000068', '0', '0', '1'),
('69', '8', 'MINDUS-SUCHEL', '0', '10000069', '10000069', '0', '0', '1'),
('71', '8', 'ALASTOR', '0', '10000071', '10000071', '0', '0', '1'),
('72', '8', 'BELLOTEX', '0', '10000072', '10000072', '0', '0', '1'),
('73', '8', 'Sucursal 3461', '0', '10000073', '10000073', '0', '0', '1'),
('74', '8', 'Sucursal 3471', '0', '10000074', '10000074', '0', '0', '1'),
('75', '8', 'Sucursal 3501', '0', '10000075', '10000075', '0', '0', '1'),
('76', '8', 'Sucursal 3701', '0', '10000076', '10000076', '0', '0', '1'),
('77', '8', 'Sucursal 3781', '0', '10000077', '10000077', '0', '0', '1'),
('78', '8', 'Sucursal 3861', '0', '10000078', '10000078', '0', '0', '1'),
('79', '8', 'Sucursal 3541', '0', '10000079', '10000079', '0', '0', '1'),
('80', '8', 'Sucursal 3721', '0', '10000080', '10000080', '0', '0', '1'),
('86', '8', 'Sucursal 3801', '0', '10000086', '10000086', '0', '0', '1'),
('87', '8', 'Sucursal 3841', '0', '10000087', '10000087', '0', '0', '1'),
('88', '8', 'Sucursal 3891', '0', '10000088', '10000088', '0', '0', '1'),
('90', '8', 'Sucursal 3581', '0', '10000090', '10000090', '0', '0', '1'),
('91', '8', 'Sucursal 3661', '0', '10000091', '10000091', '0', '0', '1'),
('92', '8', 'CADECA', '0', '10000092', '10000092', '0', '0', '1'),
('93', '8', 'Unidad Territorial de Auditor��a', '0', '10000093', '10000093', '0', '0', '1'),
('94', '8', 'Universidad de Matanzas', '0', '10000094', '10000094', '0', '0', '1'),
('95', '8', 'UMCC', '0', '10000095', '10000095', '0', '0', '1'),
('96', '8', 'OSDE-Gran Caribe', '0', '10000096', '10000096', '0', '0', '1'),
('97', '8', 'CIH', '0', '10000097', '10000097', '0', '0', '1'),
('98', '8', 'Empresa El�ctrica', '0', '10000098', '10000098', '0', '0', '1'),
('99', '8', 'Comercializadora de Combustible', '0', '10000099', '10000099', '0', '0', '1'),
('100', '8', 'Termoelectrica Antonio Guiteras', '0', '10000100', '10000100', '0', '0', '1'),
('101', '8', 'Empresa Azucarera', '0', '10000101', '10000101', '0', '0', '1'),
('102', '8', 'TRASMEC', '0', '10000102', '10000102', '0', '0', '1'),
('103', '8', 'AZUMAT Emp. Log��stica', '0', '10000103', '10000103', '0', '0', '1'),
('104', '8', 'CONAS', '0', '10000104', '10000104', '0', '0', '1'),
('105', '8', 'CITUR', '0', '10000105', '10000105', '0', '0', '1'),
('107', '8', 'Secci�n de Auditoria Central', '0', '10000107', '10000107', '0', '0', '1'),
('109', '8', 'U/M 1070', '0', '10000109', '10000109', '0', '0', '1'),
('118', '8', 'SEPSA- Mtzas', '0', '10000118', '10000118', '0', '0', '1'),
('119', '8', 'Hotel Melia Antilla', '0', '10000119', '10000119', '0', '0', '1'),
('120', '8', 'Hotel Los Delfines', '0', '10000120', '10000120', '0', '0', '1'),
('121', '8', 'Osde Gran Caribe', '0', '10000121', '10000121', '0', '0', '1'),
('122', '8', 'Osde Cubanac�n', '0', '10000122', '10000122', '0', '0', '1'),
('123', '8', 'Caracol', '0', '10000123', '10000123', '0', '0', '1'),
('124', '8', 'CTC Prov', '0', '10000124', '10000124', '0', '0', '1'),
('125', '8', 'Hotal Villa Cuba', '0', '10000125', '10000125', '0', '0', '1'),
('126', '8', 'Hotel Arenas Doradas', '0', '10000126', '10000126', '0', '0', '1'),
('127', '8', 'Hotel Melia Varadero', '0', '10000127', '10000127', '0', '0', '1'),
('128', '8', 'Palmares', '0', '10000128', '10000128', '0', '0', '1'),
('129', '8', 'Hotel Coralia Club Playa de Oro', '0', '10000129', '10000129', '0', '0', '1'),
('130', '8', 'Hotel Iberostar Varadero', '0', '10000130', '10000130', '0', '0', '1'),
('131', '8', 'Emp. Integral Pen�nsula de Zapata', '0', '10000131', '10000131', '0', '0', '1'),
('132', '8', 'Hotel Turquesa', '0', '10000132', '10000132', '0', '0', '1'),
('133', '8', 'Hotel Solymar', '0', '10000133', '10000133', '0', '0', '1'),
('134', '8', 'Cubacar', '0', '10000134', '10000134', '0', '0', '1'),
('135', '8', 'CP-PCC', '0', '10000135', '10000135', '0', '0', '1'),
('137', '8', 'ENIKA', '0', '10000137', '10000137', '0', '0', '1'),
('138', '8', 'Barlovento Hoteles C', '0', '10000138', '10000138', '0', '0', '1'),
('139', '8', 'Hotel Iberostar Ta�nos', '0', '10000139', '10000139', '0', '0', '1'),
('140', '8', 'Hotel Melia las Americas', '0', '10000140', '10000140', '0', '0', '1'),
('141', '8', 'AI-ONAT', '0', '10000141', '10000141', '0', '0', '1'),
('142', '8', 'Hotel Sol Palmeras', '0', '10000142', '10000142', '0', '0', '1'),
('143', '2', 'Emp. Materiales de la Construcci�n', '0', '10000143', '10000143', '0', '0', '1'),
('144', '8', 'Sucursal', '0', '10000144', '10000144', '0', '0', '1'),
('145', '8', 'Hotel Aguas Azules', '0', '10000145', '10000145', '0', '0', '1'),
('146', '8', 'Hotel Brezzes Bella costa', '0', '10000146', '10000146', '0', '0', '1'),
('147', '8', 'Complejo Mar del Sur Pullman Dos mares', '0', '10000147', '10000147', '0', '0', '1'),
('148', '8', 'AI-Osde Islazul', '0', '10000148', '10000148', '0', '0', '1'),
('150', '8', 'Hotel Bella Costa', '0', '10000150', '10000150', '0', '0', '1'),
('151', '8', 'Hotel Royalton Hicacos', '0', '10000151', '10000151', '0', '0', '1'),
('152', '8', 'UEB Esasucar', '0', '10000152', '10000152', '0', '0', '1'),
('153', '8', 'Esazucar', '0', '10000153', '10000153', '0', '0', '1'),
('154', '8', 'Servicos Sanitarios Prosa', '0', '10000154', '10000154', '0', '0', '1'),
('155', '8', 'ARTEX', '0', '10000155', '10000155', '0', '0', '1'),
('156', '4', 'Emp. Acopio Mtzas', '0', '10000156', '10000156', '0', '0', '1'),
('157', '8', 'BPA', '0', '10000157', '10000157', '0', '0', '1'),
('158', '6', 'Salud Col�n', '0', '10000158', '10000158', '0', '0', '1'),
('159', '8', 'Sucursal 3921', '0', '10000159', '10000159', '0', '0', '1'),
('160', '8', 'TRANSTUR', '0', '10000160', '10000160', '0', '0', '1'),
('161', '8', 'Empresa de Acueductos y Alcantarillados', '0', '10000161', '10000161', '0', '0', '1'),
('163', '8', 'Empresa Campismo Popular', '0', '10000163', '10000163', '0', '0', '1');

            
-- 
-- Vaciado de tabla 'hc'
-- 
DROP TABLE IF EXISTS `hc`;
                        
--
-- Estructura de tabla para la tabla 'hc'
--

CREATE TABLE `hc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_expediente` int(11) NOT NULL,
  `resumen` longtext COLLATE utf8mb4_unicode_ci,
  `objeto_social_entidad` longtext COLLATE utf8mb4_unicode_ci,
  `total_implicados_entidad` smallint(6) DEFAULT NULL,
  `total_implicados_otras` smallint(6) DEFAULT NULL,
  `afectacion_economica_cup` double DEFAULT NULL,
  `recuperado_cup` double DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `phc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3846C3B2A331D421` (`phc_id`),
  CONSTRAINT `FK_3846C3B2A331D421` FOREIGN KEY (`phc_id`) REFERENCES `phc` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'hc'
--

INSERT INTO `hc` (`id`, `numero_expediente`, `resumen`, `objeto_social_entidad`, `total_implicados_entidad`, `total_implicados_otras`, `afectacion_economica_cup`, `recuperado_cup`, `activo`, `phc_id`) VALUES 
('1', '4', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', '10', '2', '25.02', '25.02', '1', '1'),
('2', '3', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', '5', '8', '28.25', '20', '1', '2'),
('3', '40', 'znckjszcnkjsdcnkjsdnckjsncksdnkcnskjcnsdkjjjjjjjjjjccccccccccccc', 'knaskjnckjsdcds', '4', '15', '45.28', '40', '1', '3');

            
-- 
-- Vaciado de tabla 'implicado'
-- 
DROP TABLE IF EXISTS `implicado`;
                        
--
-- Estructura de tabla para la tabla 'implicado'
--

CREATE TABLE `implicado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_ocupacional` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `escolaridad` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pcc` tinyint(1) NOT NULL,
  `phd_id` int(11) DEFAULT NULL,
  `nivel_direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ujc` tinyint(1) NOT NULL,
  `edad` smallint(6) DEFAULT NULL,
  `sexo` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `h_c_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E641B79D3EE6EC98` (`phd_id`),
  KEY `IDX_E641B79D5FB53041` (`h_c_id`),
  CONSTRAINT `FK_E641B79D3EE6EC98` FOREIGN KEY (`phd_id`) REFERENCES `phd` (`id`),
  CONSTRAINT `FK_E641B79D5FB53041` FOREIGN KEY (`h_c_id`) REFERENCES `hc` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'implicado'
--

INSERT INTO `implicado` (`id`, `nombre`, `cargo`, `categoria_ocupacional`, `escolaridad`, `pcc`, `phd_id`, `nivel_direccion`, `ujc`, `edad`, `sexo`, `activo`, `h_c_id`) VALUES 
('1', 'Pedro Porro', 'Auditor', 'No se', '9no grado', '1', NULL, 'Trabajador', '0', '30', 'M', '1', '1'),
('2', 'Maria Cabrera', 'Limpiadora', 'Maestra', 'Tecnico medio', '0', NULL, 'Obrera', '1', '45', 'F', '1', '1'),
('3', 'Viviana Valero', 'Cualquiera', 'No se', '9no', '0', NULL, 'Trabajador', '0', '33', 'F', '1', '1'),
('4', 'Juan Castro', 'Cualquiera', 'Pelotudo', 'Tecnico medio', '0', NULL, 'No se', '1', '49', 'M', '1', '2'),
('5', 'Juan Gualberto G�mez', 'Desmochador de palmas', 'No se', 'Obrero', '0', '1', 'Obrero', '0', '45', 'M', '1', NULL),
('6', 'Pepe Mendieta', 'Econom�a', 'No se', 'Obrero', '1', '2', 'Jefe', '0', '58', 'M', '1', NULL);

            
-- 
-- Vaciado de tabla 'localidad'
-- 
DROP TABLE IF EXISTS `localidad`;
                        
--
-- Estructura de tabla para la tabla 'localidad'
--

CREATE TABLE `localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `municipio_id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4F68E0103A909126` (`nombre`),
  KEY `IDX_4F68E01058BC1BE0` (`municipio_id`),
  CONSTRAINT `FK_4F68E01058BC1BE0` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'localidad'
--

INSERT INTO `localidad` (`id`, `municipio_id`, `nombre`, `activo`) VALUES 
('1', '1', 'Matanzas', '1'),
('2', '2', 'C�rdenas', '1'),
('3', '4', 'Mart�', '1'),
('4', '3', 'Col�n', '1'),
('5', '5', 'Perico', '1'),
('6', '6', 'Jovellanos', '1'),
('7', '7', 'Pedro Betancourt', '1'),
('8', '8', 'Limonar', '1'),
('9', '9', 'Uni�n de Reyes', '1'),
('10', '10', 'Ci�naga De Zapata', '1'),
('11', '11', 'Jag�ey Grande', '1'),
('12', '12', 'Calimete', '1'),
('13', '13', 'Los Arabos', '1'),
('15', '1', 'Ceiba Mocha', '1'),
('16', '2', 'Varadero', '1'),
('17', '11', 'Agramonte', '1'),
('18', '2', 'Boca Camarioca', '1'),
('19', '9', 'Cabezas', '1'),
('21', '6', 'Carlos Rojas', '1'),
('23', '9', 'Cidra', '1'),
('27', '1', 'Guan�bana', '1'),
('32', '1', 'Madruga', '1'),
('35', '5', 'M�ximo G�mez', '1'),
('38', '10', 'Playa Larga', '1'),
('39', '2', 'Santa Marta', '1'),
('40', '1', 'Corral Nuevo', '1'),
('41', '1', 'Versalles', '1');

            
-- 
-- Vaciado de tabla 'log'
-- 
DROP TABLE IF EXISTS `log`;
                        
--
-- Estructura de tabla para la tabla 'log'
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `ip` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F3F68C5DB38439E` (`usuario_id`),
  CONSTRAINT `FK_8F3F68C5DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'log'
--

INSERT INTO `log` (`id`, `usuario_id`, `fecha`, `ip`, `accion`) VALUES 
('2', '1', '2019-12-12 22:51:55', '127.0.0.1', 'Login'),
('3', '2', '2019-12-13 01:35:58', '127.0.0.1', 'Login'),
('4', '1', '2019-12-13 01:57:36', '127.0.0.1', 'Login'),
('5', '2', '2019-12-13 01:59:41', '127.0.0.1', 'Login'),
('6', '2', '2019-12-13 05:29:06', '127.0.0.1', 'Login'),
('7', '1', '2019-12-13 05:34:03', '127.0.0.1', 'Login'),
('8', '1', '2019-12-13 05:46:17', '127.0.0.1', 'Login'),
('9', '2', '2019-12-13 05:50:55', '127.0.0.1', 'Login'),
('10', '2', '2020-01-02 21:52:07', '127.0.0.1', 'Login'),
('11', '2', '2020-01-02 21:59:40', '127.0.0.1', 'Login'),
('12', '1', '2020-01-03 01:58:39', '127.0.0.1', 'Login'),
('13', '1', '2020-01-03 02:31:19', '127.0.0.1', 'Login'),
('14', '2', '2020-01-03 02:47:46', '127.0.0.1', 'Login'),
('15', '1', '2020-01-15 19:20:51', '127.0.0.1', 'Login'),
('16', '1', '2020-01-15 19:51:18', '127.0.0.1', 'Login'),
('17', '1', '2020-01-16 02:21:49', '127.0.0.1', 'Filtrar localidad'),
('18', '1', '2020-01-16 02:24:28', '127.0.0.1', 'Filtrar localidad'),
('19', '1', '2020-01-16 02:24:53', '127.0.0.1', 'Filtrar localidad'),
('20', '1', '2020-01-16 02:28:55', '127.0.0.1', 'Filtrar localidad'),
('21', '1', '2020-01-16 02:29:32', '127.0.0.1', 'Filtrar localidad'),
('22', '1', '2020-01-16 02:29:53', '127.0.0.1', 'Filtrar localidad'),
('23', '1', '2020-01-16 02:32:22', '127.0.0.1', 'Filtrar localidad'),
('24', '1', '2020-01-16 02:32:51', '127.0.0.1', 'Filtrar localidad'),
('25', '1', '2020-01-16 02:33:17', '127.0.0.1', 'Filtrar localidad'),
('26', '3', '2020-01-16 02:41:12', '127.0.0.1', 'Login'),
('27', '1', '2020-01-16 02:45:37', '127.0.0.1', 'Login'),
('28', '1', '2020-01-16 06:07:19', '127.0.0.1', 'Login'),
('29', '1', '2020-01-16 22:52:26', '127.0.0.1', 'Modificar organismo: 1'),
('30', '1', '2020-01-16 22:53:41', '127.0.0.1', 'Modificar organismo: 1'),
('31', '1', '2020-01-16 22:54:17', '127.0.0.1', 'Modificar organismo: 1'),
('32', '1', '2020-01-16 22:54:39', '127.0.0.1', 'Modificar organismo: 1'),
('33', '1', '2020-01-16 23:16:08', '127.0.0.1', 'Insertar Osde'),
('34', '1', '2020-01-17 00:35:42', '127.0.0.1', 'Insertar entidad'),
('35', '1', '2020-01-17 00:37:32', '127.0.0.1', 'Modificar entidad: 1'),
('36', '1', '2020-01-17 00:42:06', '127.0.0.1', 'Filtrar entidad'),
('37', '1', '2020-01-17 00:42:14', '127.0.0.1', 'Filtrar entidad'),
('38', '1', '2020-01-17 02:40:02', '127.0.0.1', 'Login'),
('39', '1', '2020-01-17 03:22:29', '127.0.0.1', 'Insertar cargo'),
('40', '1', '2020-01-17 04:33:30', '127.0.0.1', 'Insertar plazas'),
('41', '1', '2020-01-17 04:35:21', '127.0.0.1', 'Insertar cargo'),
('42', '1', '2020-01-17 04:35:52', '127.0.0.1', 'Insertar plazas'),
('43', '1', '2020-01-17 06:44:41', '127.0.0.1', 'Filtrar entidad'),
('44', '1', '2020-01-17 06:46:46', '127.0.0.1', 'Filtrar entidad'),
('45', '1', '2020-01-17 06:47:05', '127.0.0.1', 'Filtrar entidad'),
('46', '1', '2020-01-17 06:48:48', '127.0.0.1', 'Filtrar entidad'),
('47', '1', '2020-01-17 06:49:53', '127.0.0.1', 'Filtrar entidad'),
('48', '1', '2020-01-17 06:52:02', '127.0.0.1', 'Filtrar plazas'),
('49', '1', '2020-01-17 06:52:11', '127.0.0.1', 'Filtrar plazas'),
('50', '1', '2020-01-17 06:52:24', '127.0.0.1', 'Filtrar plazas'),
('51', '1', '2020-01-17 16:57:52', '127.0.0.1', 'Modificar plaza: 3'),
('52', '1', '2020-01-18 21:23:02', '127.0.0.1', 'Login'),
('53', '1', '2020-01-18 21:23:20', '127.0.0.1', 'Filtrar auditores'),
('54', '1', '2020-01-18 21:23:38', '127.0.0.1', 'Filtrar auditores'),
('55', '1', '2020-01-18 21:43:02', '127.0.0.1', 'Crear reporte'),
('56', '1', '2020-01-18 21:43:25', '127.0.0.1', 'Crear reporte'),
('57', '1', '2020-01-18 21:45:53', '127.0.0.1', 'Filtrar auditores'),
('58', '1', '2020-01-18 21:46:35', '127.0.0.1', 'Crear reporte'),
('59', '1', '2020-01-19 01:56:27', '127.0.0.1', 'Login'),
('60', '1', '2020-01-19 05:18:33', '127.0.0.1', 'Filtrar plazas'),
('61', '1', '2020-01-19 05:19:57', '127.0.0.1', 'Filtrar plazas'),
('62', '1', '2020-01-23 01:26:25', '127.0.0.1', 'Modificar auditor 3'),
('63', '1', '2020-01-23 01:27:22', '127.0.0.1', 'Modificar auditor 5'),
('64', '1', '2020-02-14 16:32:54', '127.0.0.1', 'Login'),
('65', '1', '2020-02-21 12:55:20', '127.0.0.1', 'Login'),
('66', '2', '2020-02-26 20:53:07', '127.0.0.1', 'Login'),
('67', '3', '2020-02-26 20:53:46', '127.0.0.1', 'Login'),
('68', '1', '2020-02-26 20:58:30', '127.0.0.1', 'Login'),
('69', '2', '2020-02-26 21:29:25', '127.0.0.1', 'Login'),
('70', '2', '2020-02-26 21:32:23', '127.0.0.1', 'Login'),
('71', '1', '2020-02-26 21:48:23', '127.0.0.1', 'Login'),
('72', '3', '2020-02-26 22:04:56', '127.0.0.1', 'Login'),
('73', '1', '2020-02-26 22:05:39', '127.0.0.1', 'Login'),
('74', '1', '2020-04-09 17:12:58', '127.0.0.1', 'Login'),
('75', '1', '2020-04-09 17:35:24', '127.0.0.1', 'Filtrar HC'),
('76', '1', '2020-04-09 17:36:06', '127.0.0.1', 'Filtrar HC'),
('77', '1', '2020-04-09 17:36:14', '127.0.0.1', 'Filtrar HC'),
('78', '1', '2020-04-09 17:36:57', '127.0.0.1', 'Filtrar HC'),
('79', '1', '2020-04-09 17:37:17', '127.0.0.1', 'Filtrar HC'),
('80', '1', '2020-04-09 17:39:42', '127.0.0.1', 'Filtrar HC'),
('81', '1', '2020-04-09 17:39:52', '127.0.0.1', 'Filtrar HC'),
('82', '1', '2020-04-09 17:40:05', '127.0.0.1', 'Filtrar HC'),
('83', '1', '2020-04-09 17:41:02', '127.0.0.1', 'Filtrar HC'),
('84', '1', '2020-04-09 17:41:12', '127.0.0.1', 'Filtrar HC'),
('85', '1', '2020-04-09 17:41:25', '127.0.0.1', 'Filtrar HC'),
('86', '1', '2020-04-09 17:41:35', '127.0.0.1', 'Filtrar HC'),
('87', '1', '2020-04-09 17:41:58', '127.0.0.1', 'Filtrar HC'),
('88', '1', '2020-04-09 17:42:27', '127.0.0.1', 'Filtrar HC'),
('89', '1', '2020-04-09 17:42:44', '127.0.0.1', 'Filtrar HC'),
('90', '1', '2020-04-09 17:42:57', '127.0.0.1', 'Filtrar HC'),
('91', '1', '2020-04-09 17:44:07', '127.0.0.1', 'Filtrar HC'),
('92', '1', '2020-04-09 17:44:20', '127.0.0.1', 'Filtrar HC'),
('93', '1', '2020-04-09 17:45:09', '127.0.0.1', 'Filtrar HC'),
('94', '1', '2020-04-09 17:45:27', '127.0.0.1', 'Filtrar HC'),
('95', '1', '2020-04-09 17:45:43', '127.0.0.1', 'Filtrar HC'),
('96', '1', '2020-04-09 17:47:24', '127.0.0.1', 'Filtrar HC'),
('97', '1', '2020-04-09 17:47:36', '127.0.0.1', 'Filtrar HC'),
('98', '1', '2020-04-09 17:51:03', '127.0.0.1', 'Filtrar HC'),
('99', '1', '2020-04-09 17:51:18', '127.0.0.1', 'Filtrar HC'),
('100', '1', '2020-04-09 17:51:26', '127.0.0.1', 'Filtrar HC'),
('101', '1', '2020-04-09 17:51:42', '127.0.0.1', 'Filtrar HC'),
('102', '1', '2020-04-09 17:51:53', '127.0.0.1', 'Filtrar HC'),
('103', '1', '2020-04-09 17:53:27', '127.0.0.1', 'Filtrar HC'),
('104', '1', '2020-04-09 17:53:36', '127.0.0.1', 'Filtrar HC'),
('105', '1', '2020-04-09 17:53:49', '127.0.0.1', 'Filtrar HC'),
('106', '1', '2020-04-09 17:53:59', '127.0.0.1', 'Filtrar HC'),
('107', '1', '2020-04-09 17:54:11', '127.0.0.1', 'Filtrar HC'),
('108', '1', '2020-04-09 17:59:36', '127.0.0.1', 'Filtrar HC'),
('109', '1', '2020-04-09 17:59:46', '127.0.0.1', 'Filtrar HC'),
('110', '1', '2020-04-09 17:59:56', '127.0.0.1', 'Filtrar HC'),
('111', '1', '2020-04-09 18:02:21', '127.0.0.1', 'Crear reporte HC'),
('112', '1', '2020-04-10 17:01:24', '127.0.0.1', 'Actualizar usuario: 1'),
('113', '1', '2020-04-10 17:08:19', '127.0.0.1', 'Insertar particularidad'),
('114', '1', '2020-04-10 17:08:40', '127.0.0.1', 'Insertar particularidad'),
('115', '1', '2020-04-10 17:09:04', '127.0.0.1', 'Insertar particularidad'),
('116', '1', '2020-04-10 17:09:33', '127.0.0.1', 'Insertar particularidad'),
('117', '1', '2020-04-10 17:09:41', '127.0.0.1', 'Insertar particularidad'),
('118', '1', '2020-04-10 17:10:05', '127.0.0.1', 'Insertar particularidad'),
('119', '1', '2020-04-10 17:11:56', '127.0.0.1', 'Insertar tipo de acci�n'),
('120', '1', '2020-04-10 17:12:01', '127.0.0.1', 'Insertar tipo de acci�n'),
('121', '1', '2020-04-10 17:12:16', '127.0.0.1', 'Insertar tipo de acci�n'),
('122', '1', '2020-04-10 17:13:35', '127.0.0.1', 'Insertar causa'),
('123', '1', '2020-04-10 17:13:41', '127.0.0.1', 'Insertar causa'),
('124', '1', '2020-04-10 17:13:55', '127.0.0.1', 'Insertar causa'),
('125', '1', '2020-04-10 17:14:06', '127.0.0.1', 'Insertar causa'),
('126', '1', '2020-04-10 17:15:17', '127.0.0.1', 'Insertar situaci�n del PHD'),
('127', '1', '2020-04-10 17:15:43', '127.0.0.1', 'Insertar situaci�n del PHD'),
('128', '1', '2020-04-10 17:16:03', '127.0.0.1', 'Insertar situaci�n del PHD'),
('129', '1', '2020-04-10 17:16:38', '127.0.0.1', 'Insertar situaci�n del PHD'),
('130', '1', '2020-04-10 17:17:01', '127.0.0.1', 'Insertar situaci�n del PHD'),
('131', '1', '2020-04-10 17:19:09', '127.0.0.1', 'Insertar PHD'),
('132', '1', '2020-04-10 17:22:25', '127.0.0.1', 'Insertar PHD'),
('133', '1', '2020-04-10 17:22:50', '127.0.0.1', 'Filtrar PHD'),
('134', '1', '2020-04-10 20:40:04', '127.0.0.1', 'Login'),
('135', '1', '2020-04-13 23:03:35', '127.0.0.1', 'Insertar acci�n de control'),
('136', '1', '2020-04-13 23:04:23', '127.0.0.1', 'Modificar PHD: 1'),
('137', '1', '2020-04-14 11:53:25', '127.0.0.1', 'Modificar PHC: 1'),
('138', '1', '2020-04-14 11:53:54', '127.0.0.1', 'Modificar PHC: 4'),
('139', '1', '2020-04-14 12:42:20', '127.0.0.1', 'Insertar acci�n de control'),
('140', '2', '2020-04-14 15:25:02', '127.0.0.1', 'Login'),
('141', '1', '2020-04-20 14:20:37', '127.0.0.1', 'Login'),
('142', '1', '2020-04-20 14:21:40', '127.0.0.1', 'Actualizar usuario: 1'),
('143', '1', '2020-04-20 17:47:38', '127.0.0.1', 'Salvar Database'),
('144', '1', '2020-04-20 17:48:04', '127.0.0.1', 'Salvar Database'),
('145', '1', '2020-04-20 18:32:09', '127.0.0.1', 'Salvar Database'),
('146', '1', '2020-04-20 18:32:16', '127.0.0.1', 'Salvar Database'),
('147', '1', '2020-04-20 18:32:32', '127.0.0.1', 'Salvar Database'),
('148', '1', '2020-04-20 18:33:58', '127.0.0.1', 'Salvar Database'),
('149', '1', '2020-04-20 18:34:28', '127.0.0.1', 'Salvar Database'),
('150', '1', '2020-04-20 18:39:41', '127.0.0.1', 'Salvar Database'),
('151', '1', '2020-04-20 18:39:50', '127.0.0.1', 'Salvar Database'),
('152', '1', '2020-04-20 18:40:02', '127.0.0.1', 'Salvar Database'),
('153', '1', '2020-04-20 18:45:53', '127.0.0.1', 'Salvar Database'),
('154', '1', '2020-04-20 18:47:08', '127.0.0.1', 'Salvar Database'),
('155', '1', '2020-04-20 18:48:17', '127.0.0.1', 'Salvar Database'),
('156', '1', '2020-04-20 18:52:24', '127.0.0.1', 'Salvar Database'),
('157', '1', '2020-04-20 18:52:33', '127.0.0.1', 'Salvar Database'),
('158', '1', '2020-04-20 18:55:07', '127.0.0.1', 'Salvar Database'),
('159', '1', '2020-04-20 18:55:34', '127.0.0.1', 'Salvar Database'),
('160', '1', '2020-04-20 18:56:01', '127.0.0.1', 'Salvar Database'),
('161', '1', '2020-04-20 18:56:35', '127.0.0.1', 'Salvar Database'),
('162', '1', '2020-04-20 18:58:08', '127.0.0.1', 'Salvar Database'),
('163', '1', '2020-04-20 18:59:21', '127.0.0.1', 'Salvar Database'),
('164', '1', '2020-04-20 18:59:58', '127.0.0.1', 'Salvar Database'),
('165', '1', '2020-04-20 19:00:28', '127.0.0.1', 'Salvar Database'),
('166', '1', '2020-04-20 19:00:47', '127.0.0.1', 'Salvar Database'),
('167', '1', '2020-04-20 19:01:47', '127.0.0.1', 'Salvar Database'),
('168', '1', '2020-04-20 19:02:36', '127.0.0.1', 'Salvar Database'),
('169', '1', '2020-04-20 19:03:26', '127.0.0.1', 'Salvar Database'),
('170', '1', '2020-04-20 19:03:51', '127.0.0.1', 'Salvar Database'),
('171', '1', '2020-04-20 19:04:09', '127.0.0.1', 'Salvar Database'),
('172', '1', '2020-04-20 19:04:15', '127.0.0.1', 'Salvar Database'),
('173', '1', '2020-04-20 19:05:04', '127.0.0.1', 'Salvar Database'),
('174', '1', '2020-04-20 19:05:10', '127.0.0.1', 'Salvar Database'),
('175', '1', '2020-04-20 19:05:16', '127.0.0.1', 'Salvar Database'),
('176', '1', '2020-04-20 21:51:56', '127.0.0.1', 'Salvar Database'),
('177', '1', '2020-05-18 15:10:50', '127.0.0.1', 'Login'),
('178', '2', '2020-05-18 15:15:37', '127.0.0.1', 'Login'),
('179', '1', '2020-06-11 20:51:20', '127.0.0.1', 'Login'),
('180', '1', '2020-06-21 21:51:06', '127.0.0.1', 'Login');

            
-- 
-- Vaciado de tabla 'medida_disciplinaria'
-- 
DROP TABLE IF EXISTS `medida_disciplinaria`;
                        
--
-- Estructura de tabla para la tabla 'medida_disciplinaria'
--

CREATE TABLE `medida_disciplinaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'medida_disciplinaria'
--

INSERT INTO `medida_disciplinaria` (`id`, `categoria`, `nombre`, `activo`) VALUES 
('1', 'Trabajadores', 'Amonestaci�n', '1'),
('2', 'Trabajadores', 'Multas', '1'),
('3', 'Directivos', 'Amonestaci�n', '1'),
('4', 'Directivos superiores', 'Amonestaci�n', '1'),
('5', 'Trabajadores', 'Democi�n temporal', '1'),
('6', 'Directivos superiores', 'Multas', '1');

            
-- 
-- Vaciado de tabla 'migration_versions'
-- 
DROP TABLE IF EXISTS `migration_versions`;
                        
--
-- Estructura de tabla para la tabla 'migration_versions'
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'migration_versions'
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES 
('20191205173401', '2019-12-05 17:42:16'),
('20191206200847', '2019-12-06 20:09:02'),
('20191206202119', '2019-12-06 20:21:41'),
('20191207002151', '2019-12-07 00:22:08'),
('20191207002642', '2019-12-07 00:27:33'),
('20191212215106', '2019-12-12 21:51:16'),
('20200115183631', '2020-01-15 18:38:05'),
('20200116173127', '2020-01-16 17:31:38'),
('20200116235152', '2020-01-16 23:52:04'),
('20200117040629', '2020-01-17 04:06:42'),
('20200121234505', '2020-01-21 23:45:41'),
('20200121235324', '2020-01-21 23:53:37'),
('20200122002252', '2020-01-22 00:23:05'),
('20200122002811', '2020-01-22 00:28:19'),
('20200122021943', '2020-01-22 02:19:53'),
('20200313022013', '2020-04-09 22:10:55'),
('20200313023415', '2020-04-09 22:10:56'),
('20200315230926', '2020-04-09 22:10:56'),
('20200315234050', '2020-04-09 22:11:00'),
('20200317002427', '2020-04-09 22:11:01'),
('20200317004356', '2020-04-09 22:11:05'),
('20200317011252', '2020-04-09 22:11:07'),
('20200317011727', '2020-04-09 22:11:07'),
('20200317194745', '2020-04-09 22:11:13'),
('20200317205148', '2020-04-09 22:11:19'),
('20200320205115', '2020-04-09 22:11:20'),
('20200323000334', '2020-04-09 22:11:25'),
('20200323003312', '2020-04-09 22:11:25'),
('20200329152120', '2020-04-09 22:11:35'),
('20200329155003', '2020-04-09 22:11:36'),
('20200404173051', '2020-04-09 22:11:37'),
('20200404201326', '2020-04-09 22:11:41'),
('20200409211929', '2020-04-09 22:11:43'),
('20200409213030', '2020-04-09 22:11:44'),
('20200409220621', '2020-04-09 22:11:47'),
('20200409225748', '2020-04-09 22:58:01'),
('20200420195713', '2020-04-20 19:57:42');

            
-- 
-- Vaciado de tabla 'municipio'
-- 
DROP TABLE IF EXISTS `municipio`;
                        
--
-- Estructura de tabla para la tabla 'municipio'
--

CREATE TABLE `municipio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FE98F5E03A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'municipio'
--

INSERT INTO `municipio` (`id`, `nombre`, `activo`) VALUES 
('1', 'Matanzas', '1'),
('2', 'C�rdenas', '1'),
('3', 'Col�n', '1'),
('4', 'Mart�', '1'),
('5', 'Perico', '1'),
('6', 'Jovellanos', '1'),
('7', 'Pedro Betancourt', '1'),
('8', 'Limonar', '1'),
('9', 'Uni�n de Reyes', '1'),
('10', 'Ci�naga de Zapata', '1'),
('11', 'Jag�ey Grande', '1'),
('12', 'Calimete', '1'),
('13', 'Los Arabos', '1');

            
-- 
-- Vaciado de tabla 'nivel'
-- 
DROP TABLE IF EXISTS `nivel`;
                        
--
-- Estructura de tabla para la tabla 'nivel'
--

CREATE TABLE `nivel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_AAFC20CB3A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'nivel'
--

INSERT INTO `nivel` (`id`, `nombre`, `activo`) VALUES 
('1', 'Tec. Medio Comercio', '0'),
('2', 'Tec. Medio Contador                          ', '0'),
('3', 'Tec.Medio                                    ', '0'),
('4', 'Contador planificador                        ', '0'),
('5', 'Ing Mecánico                                 ', '0'),
('6', 'Ing. Civil                                   ', '0'),
('7', 'Ing. Informática                             ', '0'),
('8', 'Ing. Mecanización                            ', '0'),
('9', 'Ing. Químico                                 ', '0'),
('10', 'Ing. Industrial                              ', '0'),
('11', 'Ing. Agrónomo                                ', '0'),
('12', 'Ing. Ciencias Informaticas', '0'),
('13', 'Jurista                                      ', '0'),
('14', 'Lic. Ciencias Sociales                       ', '0'),
('15', 'Lic. Contabilidad                            ', '0'),
('16', 'Lic. Economía                                ', '0'),
('17', 'Lic. Contabilidad y Finanzas                 ', '0'),
('18', 'Lic. Derecho                                 ', '0'),
('19', 'Lic Control Económico                        ', '0'),
('20', 'Lic Educación Esp. Economía.', '0'),
('21', 'Lic Estudios Socioculturales                 ', '0'),
('22', 'Lic. en Cultura Física                       ', '0'),
('23', 'Lic. en Educación                            ', '0'),
('24', 'Lic. Cibernética                             ', '0'),
('25', 'Lic. Dirección de la Economía', '0'),
('26', 'Lic. Economia- Msc. Administración de Empresa', '0'),
('27', 'Lic. Economía Msc Administración de Negocios.', '0'),
('28', 'Lic. Educación                               ', '0'),
('29', 'Lic. Educación Especialidad  Informática', '0'),
('30', 'Lic. Educación Especialidad Economía', '0'),
('31', 'Lic. en Dirección de Auditoría', '0'),
('32', 'Master Adm. de Emp.Lic Control Económico', '0'),
('33', 'Master en Administración de Empresas', '0'),
('34', 'Master en Administración Tributaria          ', '0'),
('35', 'Master en Procesos Gerenciales               ', '0'),
('36', 'Msc en Administracion Financiera             ', '0'),
('37', 'Msc en Estudios Sociales                     ', '0'),
('38', 'Msc en Gestión Contable y Financiera del Turismo', '0'),
('39', 'Tec Medio Avícola en Zootenia                ', '0'),
('40', 'Tec Medio Planificación                      ', '0'),
('41', 'Tec Medio en Información Económica           ', '0'),
('42', 'Tec. Información Económica                   ', '0'),
('43', 'Tec. Medio Planificación                     ', '0'),
('44', 'Tec. Medio Sanidad Vegetal                   ', '0'),
('45', 'Tec. Medio en Informatica                    ', '0'),
('46', 'Técnico Económico                            ', '0'),
('47', 'Técnico Medio Finanzas                       ', '0'),
('48', 'Universitario                                ', '0'),
('49', 'Lic. Contabilidad y Finanzas 2', '0'),
('50', 'Ingeniero Pecuario', '0');

            
-- 
-- Vaciado de tabla 'organismo'
-- 
DROP TABLE IF EXISTS `organismo`;
                        
--
-- Estructura de tabla para la tabla 'organismo'
--

CREATE TABLE `organismo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controlador` tinyint(1) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3DDAAC2D3A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'organismo'
--

INSERT INTO `organismo` (`id`, `nombre`, `controlador`, `activo`) VALUES 
('1', 'CGR', '1', '0'),
('2', 'MICONS', '0', '0'),
('3', 'MINAL', '0', '0'),
('4', 'MINAG', '0', '0'),
('5', 'AUDITA', '0', '0'),
('6', 'CAP', '0', '0'),
('7', 'CANEC', '0', '0'),
('8', 'BPA', '0', '0'),
('9', 'BANDEC', '0', '0'),
('10', 'CADECA', '0', '0'),
('11', 'ONAT', '0', '0'),
('12', 'FBC', '0', '0'),
('13', 'IACC', '0', '0'),
('14', 'MINTUR', '0', '0'),
('15', 'MINDUS', '0', '0'),
('16', 'MES', '0', '0'),
('17', 'MINEM', '0', '0'),
('18', 'AZCUBA', '0', '0'),
('19', 'CONAS', '0', '0'),
('20', 'PROSA', '0', '0'),
('21', 'ARTEX', '0', '0'),
('22', 'NAVEGACION CARIBE', '0', '0'),
('23', 'CORREOS', '0', '0'),
('24', 'ACUEDUCTO', '0', '0'),
('27', 'MFP', '0', '0'),
('32', 'EISA-MINDUS', '0', '0'),
('33', 'BCC', '0', '0'),
('38', 'MINCULT', '0', '0'),
('42', 'MINFAR', '0', '0'),
('46', 'Osde Islazul', '0', '0'),
('47', 'Osde Servitur', '0', '0'),
('48', 'Organizaciones de Masas', '0', '0'),
('49', 'CP-PCC', '0', '0'),
('52', 'INRH', '0', '0'),
('53', 'OSDE Campismo Popular', '0', '0'),
('54', 'Sociedades Civiles', '0', '0');

            
-- 
-- Vaciado de tabla 'osde'
-- 
DROP TABLE IF EXISTS `osde`;
                        
--
-- Estructura de tabla para la tabla 'osde'
--

CREATE TABLE `osde` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organismo_id` int(11) NOT NULL,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_28FFD5343A909126` (`nombre`),
  KEY `IDX_28FFD5343260D891` (`organismo_id`),
  CONSTRAINT `FK_28FFD5343260D891` FOREIGN KEY (`organismo_id`) REFERENCES `organismo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'osde'
--

INSERT INTO `osde` (`id`, `organismo_id`, `nombre`, `activo`) VALUES 
('1', '1', 'CONTRALOR�A PROVINCIAL', '0'),
('2', '2', 'Construcci�n y Montaje', '0'),
('3', '14', 'Gran Caribe', '0'),
('4', '14', 'Cubanac�n', '0'),
('5', '54', 'Audita', '0'),
('6', '54', 'CANEC', '0'),
('7', '4', 'UAI MINAGRI', '0'),
('8', '24', 'Prueba1', '0');

            
-- 
-- Vaciado de tabla 'particularidad'
-- 
DROP TABLE IF EXISTS `particularidad`;
                        
--
-- Estructura de tabla para la tabla 'particularidad'
--

CREATE TABLE `particularidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siglas` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'particularidad'
--

INSERT INTO `particularidad` (`id`, `nombre`, `siglas`, `activo`) VALUES 
('1', 'Entidades que aplican el Perfeccionamiento Empresarial', 'PE', '1'),
('2', 'Empresas', 'EMP', '1'),
('3', 'Bancos', 'BANC', '1'),
('4', 'Unidades o Empresas Presupuestadas', 'UP', '1'),
('5', 'Uniones', 'U', '1'),
('6', 'Grupos', 'GRUP', '1');

            
-- 
-- Vaciado de tabla 'phc'
-- 
DROP TABLE IF EXISTS `phc`;
                        
--
-- Estructura de tabla para la tabla 'phc'
--

CREATE TABLE `phc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `municipio_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuente` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_deteccion` date NOT NULL,
  `fecha_ocurrencia` date NOT NULL,
  `resumen` longtext COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL,
  `accion_control_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D22F600F6CA204EF` (`entidad_id`),
  KEY `IDX_D22F600F58BC1BE0` (`municipio_id`),
  KEY `IDX_D22F600F8E7F3D91` (`accion_control_id`),
  CONSTRAINT `FK_D22F600F58BC1BE0` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`),
  CONSTRAINT `FK_D22F600F6CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`),
  CONSTRAINT `FK_D22F600F8E7F3D91` FOREIGN KEY (`accion_control_id`) REFERENCES `accion_control` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'phc'
--

INSERT INTO `phc` (`id`, `entidad_id`, `municipio_id`, `numero`, `categoria`, `provincia`, `fuente`, `fecha_deteccion`, `fecha_ocurrencia`, `resumen`, `activo`, `accion_control_id`) VALUES 
('1', '5', '4', '4', 'Trabajadores', 'Matanzas', 'no se', '2020-04-08', '2020-04-01', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', '1', '1'),
('2', '1', '1', '5', 'Trabajador', 'Matanzas', 'Cualquier cosa', '2020-04-30', '2020-04-22', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', '1', NULL),
('3', '1', '1', '6', 'Trabajadores', 'Matanzas', 'no se', '2020-04-21', '2020-04-01', 'El objetivo de esta investigaci�n es desarrollar una aplicaci�n web para controlar la gesti�n de la informaci�n asociada al Sistema Territorial de Auditor�a de la Contralor�a Provincial de Matanzas, para poder aumentar la eficiencia por tiempo y esfuerzo del trabajo; como m�todos en la obtenci�n de la informaci�n se utilizaron los te�ricos de inducci�n-deducci�n e hist�rico y como emp�ricos la entrevista y la encuesta; se desarroll� un trabajo de an�lisis que llev� al dise�o de una base de datos y al desarrollo de una aplicaci�n web de f�cil uso que permite la introducci�n y actualizaci�n de forma pr�ctica de los datos, adem�s de una eficiente recuperaci�n, organizaci�n y control de una informaci�n variada.', '1', NULL),
('4', '12', '2', '56', 'Trabajadores', 'Matanzas', 'no se', '2020-04-21', '2020-04-10', 'REsumen', '1', '1');

            
-- 
-- Vaciado de tabla 'phd'
-- 
DROP TABLE IF EXISTS `phd`;
                        
--
-- Estructura de tabla para la tabla 'phd'
--

CREATE TABLE `phd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `unidad_organizativa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_accion_id` int(11) DEFAULT NULL,
  `situacion_id` int(11) NOT NULL,
  `causa_condicion_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `numero_expediente` int(11) DEFAULT NULL,
  `numero_causa` int(11) DEFAULT NULL,
  `dano_economico_cup` double DEFAULT NULL,
  `dano_economico_otra_moneda` double DEFAULT NULL,
  `sintesis` longtext COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL,
  `accion_control_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C4BF5AC6CA204EF` (`entidad_id`),
  KEY `IDX_4C4BF5ACDD25ED3B` (`tipo_accion_id`),
  KEY `IDX_4C4BF5AC96714AEF` (`situacion_id`),
  KEY `IDX_4C4BF5AC42F8E36A` (`causa_condicion_id`),
  KEY `IDX_4C4BF5AC8E7F3D91` (`accion_control_id`),
  CONSTRAINT `FK_4C4BF5AC42F8E36A` FOREIGN KEY (`causa_condicion_id`) REFERENCES `causa_condicion` (`id`),
  CONSTRAINT `FK_4C4BF5AC6CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`),
  CONSTRAINT `FK_4C4BF5AC8E7F3D91` FOREIGN KEY (`accion_control_id`) REFERENCES `accion_control` (`id`),
  CONSTRAINT `FK_4C4BF5AC96714AEF` FOREIGN KEY (`situacion_id`) REFERENCES `situacion` (`id`),
  CONSTRAINT `FK_4C4BF5ACDD25ED3B` FOREIGN KEY (`tipo_accion_id`) REFERENCES `tipo_accion` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'phd'
--

INSERT INTO `phd` (`id`, `entidad_id`, `unidad_organizativa`, `tipo_accion_id`, `situacion_id`, `causa_condicion_id`, `fecha`, `numero_expediente`, `numero_causa`, `dano_economico_cup`, `dano_economico_otra_moneda`, `sintesis`, `activo`, `accion_control_id`) VALUES 
('1', '71', 'Ecoa', '2', '1', '3', '2020-04-16', '15', '15', '12345.12', '1345.12', 'BlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBla\r\nBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBla\r\nBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBla\r\nBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBlaBla', '1', '1'),
('2', '1', 'CGR', '1', '3', '2', '2020-04-10', '18', '15', NULL, NULL, 'kjsnjkascnkascnkasncasckjasnckasc', '1', NULL);

            
-- 
-- Vaciado de tabla 'plaza'
-- 
DROP TABLE IF EXISTS `plaza`;
                        
--
-- Estructura de tabla para la tabla 'plaza'
--

CREATE TABLE `plaza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `plazas` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E8703ECC6CA204EF` (`entidad_id`),
  KEY `IDX_E8703ECC813AC380` (`cargo_id`),
  CONSTRAINT `FK_E8703ECC6CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`),
  CONSTRAINT `FK_E8703ECC813AC380` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'plaza'
--

INSERT INTO `plaza` (`id`, `entidad_id`, `cargo_id`, `plazas`, `activo`) VALUES 
('1', '1', '1', '1', '0'),
('2', '1', '3', '2', '0'),
('3', '1', '2', '3', '0'),
('4', '1', '4', '1', '0'),
('5', '1', '5', '4', '0'),
('6', '1', '6', '4', '0'),
('7', '1', '9', '9', '0'),
('8', '1', '7', '6', '0'),
('9', '1', '8', '2', '0'),
('10', '1', '10', '2', '0'),
('11', '1', '21', '1', '0');

            
-- 
-- Vaciado de tabla 'responsabilidad'
-- 
DROP TABLE IF EXISTS `responsabilidad`;
                        
--
-- Estructura de tabla para la tabla 'responsabilidad'
--

CREATE TABLE `responsabilidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activo` tinyint(1) NOT NULL,
  `h_c_id` int(11) DEFAULT NULL,
  `medidas_total` smallint(6) DEFAULT NULL,
  `medidas_pendientes` smallint(6) DEFAULT NULL,
  `medida_disciplinaria_id` int(11) NOT NULL,
  `implicado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DBB7ACE839D5A70C` (`implicado_id`),
  KEY `IDX_DBB7ACE85FB53041` (`h_c_id`),
  KEY `IDX_DBB7ACE8EBFA8C82` (`medida_disciplinaria_id`),
  CONSTRAINT `FK_DBB7ACE839D5A70C` FOREIGN KEY (`implicado_id`) REFERENCES `implicado` (`id`),
  CONSTRAINT `FK_DBB7ACE85FB53041` FOREIGN KEY (`h_c_id`) REFERENCES `hc` (`id`),
  CONSTRAINT `FK_DBB7ACE8EBFA8C82` FOREIGN KEY (`medida_disciplinaria_id`) REFERENCES `medida_disciplinaria` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'responsabilidad'
--

INSERT INTO `responsabilidad` (`id`, `activo`, `h_c_id`, `medidas_total`, `medidas_pendientes`, `medida_disciplinaria_id`, `implicado_id`) VALUES 
('1', '1', '1', '10', '5', '1', '1'),
('2', '1', '1', '10', '0', '3', '2'),
('3', '1', '1', '5', '4', '5', '3'),
('4', '1', '2', '3', '0', '3', '4');

            
-- 
-- Vaciado de tabla 'situacion'
-- 
DROP TABLE IF EXISTS `situacion`;
                        
--
-- Estructura de tabla para la tabla 'situacion'
--

CREATE TABLE `situacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `emisor` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_21E3F5EC3A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'situacion'
--

INSERT INTO `situacion` (`id`, `nombre`, `fecha`, `emisor`, `activo`) VALUES 
('1', 'Devuelto a la unidad Auditora', '2020-04-09', NULL, '1'),
('2', 'Pendiente en el Grupo de An�lisis', '2020-04-16', NULL, '1'),
('3', 'Denuncia', '2020-04-11', NULL, '1'),
('4', 'Expediente Investigativo', '2020-04-18', '�rgano de Instrucci�n Penal', '1'),
('5', 'Pendiente a conclusi�n provisional', '2020-04-03', 'Fiscal�a', '1');

            
-- 
-- Vaciado de tabla 'tipo_accion'
-- 
DROP TABLE IF EXISTS `tipo_accion`;
                        
--
-- Estructura de tabla para la tabla 'tipo_accion'
--

CREATE TABLE `tipo_accion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1FBB15AC3A909126` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'tipo_accion'
--

INSERT INTO `tipo_accion` (`id`, `nombre`, `activo`) VALUES 
('1', 'AC', '1'),
('2', 'AF', '1'),
('3', 'VSC', '1');

            
-- 
-- Vaciado de tabla 'user'
-- 
DROP TABLE IF EXISTS `user`;
                        
--
-- Estructura de tabla para la tabla 'user'
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `entidad_id` int(11) DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D6496CA204EF` (`entidad_id`),
  CONSTRAINT `FK_8D93D6496CA204EF` FOREIGN KEY (`entidad_id`) REFERENCES `entidad` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
--
-- Volcado de datos para la tabla 'user'
--

INSERT INTO `user` (`id`, `email`, `username`, `roles`, `password`, `is_active`, `entidad_id`, `avatar`) VALUES 
('1', 'admin@nauta.cu', 'admin', '[\"ROLE_SUPER_ADMIN\",\"ROLE_ADMIN\"]', '$2y$13$Wy8I9AziEeYooD5vexcwg.eZ6rB4lg1WbXSGbkE2ZGk3XtnE8J3Uy', '1', NULL, 'avatar7.jpg'),
('2', 'arian@nauta.cu', 'arian', '[\"ROLE_ADMIN\",\"ROLE_SUPERVISOR\"]', '$2y$13$qdio7rgFELyHTy49NSFEvemFtWXaJCQ.H/4zyRljKGTuqcmf3s/N.', '1', NULL, ''),
('3', 'prueba@nauta.cu', 'prueba', '[\"ROLE_SUPERVISOR\"]', '$2y$13$nS8M9EHQpo9p0eoWqRIkteZh/0MyLQXhU8yo7DVIVlFGFXmF4g6oi', '1', NULL, ''),
('4', 'user@nauta.cu', 'user', '[\"ROLE_USER\"]', '$2y$13$b9V3/yW50qYoHCWjkt1TDeuhxEVkT9HmiZFYH1TKGTkRXO2rSTsiC', '1', NULL, '');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;