SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `arame` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `arame`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `borrar_recibos`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `borrar_recibos` ()   DELETE FROM `recibo`$$

DROP PROCEDURE IF EXISTS `comprobar_login`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `comprobar_login` (IN `nombreUsuario` VARCHAR(50), IN `passUsuario` VARCHAR(100))  NO SQL SELECT
`usuario`.`usr_username` 'username',
`usuario`.`usr_nombre` 'nombre',
`usuario`.`usr_socia` 'socia',
`usuario`.`usr_rol` 'rol'
FROM `usuario`
WHERE `usuario`.`usr_username`=nombreUsuario
AND `usuario`.`usr_pass`=passUsuario$$

DROP PROCEDURE IF EXISTS `consultar_datos_recibo_socia`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_datos_recibo_socia` (IN `socia_cod` INT(5))  NO SQL SELECT
`socia`.`soc_cod` cod,
`socia`.`soc_nif` nif,
`socia`.`soc_nombre` nombre,
`socia`.`soc_apellidos` apellidos,
`socia`.`soc_fact_nombre` fact_nombre,
`socia`.`soc_fact_dir` direccion,
`socia`.`soc_fact_nif` nif,
`socia`.`soc_fact_cp` cp,
`socia`.`soc_fact_poblacion` poblacion,
`socia`.`soc_fact_provincia` provincia,
`socia`.`soc_fact_pais` pais,
`socia`.`soc_metodo_pago` metodo_pago,
`socia`.`soc_iban` iban,
`socia`.`soc_alta` alta,
`cuota`.`cuota_cuantia` cuota
FROM `socia` 
LEFT JOIN `cuota` ON `socia`.`soc_cuota`=`cuota`.`cuota_nombre`
WHERE `socia`.`soc_cod`=`socia_cod`$$

DROP PROCEDURE IF EXISTS `consultar_empresa`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_empresa` (IN `cif` VARCHAR(10))   SELECT
    `empresa`.`emp_cif` nif,
    `empresa`.`emp_nombre` nombre,
    `empresa`.`emp_dir` dir,
    `empresa`.`emp_cp` cp,
    `empresa`.`emp_poblacion` poblacion,
    `empresa`.`emp_provincia` provincia,
    `empresa`.`emp_pais` pais,
    `empresa`.`emp_iban` iban,
    `empresa`.`emp_email` email,
    `empresa`.`emp_web` sitio_web,
    `empresa`.`emp_tlf` telefono,
    `empresa`.`emp_tlf_2` telefono_2,
    `empresa`.`emp_fax` fax,
    `empresa`.`emp_num_trabajadores` num_trabajadores,
    `empresa`.`emp_year_fundacion` year_fundacion,
    `empresa`.`emp_descripcion` descripcion,
	`empresa`.`emp_es_autonoma` es_autonoma,
	`empresa`.`emp_notas` notas
FROM
    `empresa`
WHERE
`empresa`.`emp_cif` = cif$$

DROP PROCEDURE IF EXISTS `consultar_empresas_socia`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_empresas_socia` (IN `soc` INT(5))   SELECT
    `socias_pertenecen_empresas`.`sociasoc_cod` socia,
    `socias_pertenecen_empresas`.`Empresaemp_cif` cif,
    `empresa`.`emp_nombre` empresa
FROM
    `socias_pertenecen_empresas`  LEFT JOIN `empresa` ON
    `socias_pertenecen_empresas`.`Empresaemp_cif` = `empresa`.`emp_cif`
WHERE
    `socias_pertenecen_empresas`.`sociasoc_cod` = soc$$

DROP PROCEDURE IF EXISTS `consultar_recibo`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_recibo` (IN `yr` INT(4), IN `sem` VARCHAR(10), IN `cod` INT(5))  NO SQL SELECT
`recibo`.`rec_year` year,
`recibo`.`rec_semestre` semestre,
`recibo`.`rec_cod` cod,
`recibo`.`rec_cuantia` cuantia,
`recibo`.`rec_concepto` concepto,
`recibo`.`rec_nombre` nombre,
`recibo`.`rec_nif` nif,
`recibo`.`rec_direccion` direccion,
`recibo`.`rec_cp` cp,
`recibo`.`rec_poblacion` poblacion,
`recibo`.`rec_provincia` provincia,
`recibo`.`rec_pais` pais,
`recibo`.`rec_fecha` fecha,
`recibo`.`rec_socia` socia,
`recibo`.`rec_iban` iban,
`recibo`.`rec_metodo_pago` metodo_pago,
`recibo`.`rec_fecha_baja` fecha_baja
FROM `recibo`
WHERE
`recibo`.`rec_year` = yr AND
`recibo`.`rec_semestre` = sem AND
`recibo`.`rec_cod` = cod$$

DROP PROCEDURE IF EXISTS `consultar_recibos_semestre`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_recibos_semestre` (IN `yr` INT(4), IN `sem` VARCHAR(10))   SELECT
    `recibo`.`rec_year` year,
    `recibo`.`rec_semestre` semestre,
    `recibo`.`rec_cod` cod,
    `recibo`.`rec_cuantia` cuantia,
    `recibo`.`rec_concepto` concepto,
    `recibo`.`rec_nombre` nombre,
    `recibo`.`rec_nif` nif,
    `recibo`.`rec_direccion` direccion,
    `recibo`.`rec_cp` cp,
    `recibo`.`rec_poblacion` poblacion,
    `recibo`.`rec_provincia` provincia,
    `recibo`.`rec_pais` pais,
    `recibo`.`rec_fecha` fecha,
    `recibo`.`rec_socia` socia,
    `recibo`.`rec_iban` iban,
    `recibo`.`rec_metodo_pago` metodo_pago,
    `recibo`.`rec_fecha_baja` fecha_baja,
    `socia`.`soc_nombre` nombre_socia,
    `socia`.`soc_apellidos` apellidos_socia,
    PREMIOS.`premio_year` premio_year
FROM
    `recibo`
LEFT JOIN `socia` ON `recibo`.`rec_socia` = `socia`.`soc_cod`
LEFT JOIN(
    SELECT *
    FROM
        `premio`
    WHERE
        `premio_year` = yr
) `PREMIOS`
ON
    `socia`.`soc_cod` = `PREMIOS`.`premio_socia`
WHERE
    `recibo`.`rec_year` = yr AND `recibo`.`rec_semestre` = sem$$

DROP PROCEDURE IF EXISTS `consultar_sectores_empresa`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_sectores_empresa` (IN `empresa` VARCHAR(10))   SELECT `empresas_pertenecen_sectores`.`eps_sector` nombre,
`empresas_pertenecen_sectores`.`eps_empresa` empresa
FROM `empresas_pertenecen_sectores` WHERE `empresas_pertenecen_sectores`.`eps_empresa`=empresa$$

DROP PROCEDURE IF EXISTS `consultar_semestres_year`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_semestres_year` (IN `year` INT(4))   SELECT DISTINCT `recibo`.`rec_semestre` semestre FROM `recibo` WHERE `recibo`.`rec_year` = year$$

DROP PROCEDURE IF EXISTS `consultar_socia`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_socia` (IN `codsocia` INT(5))   SELECT
    `socia`.`soc_cod` `cod`,
    `socia`.`soc_nif` `nif`,
    `socia`.`soc_alta` `alta`,
    `socia`.`soc_nombre` `nombre`,
    `socia`.`soc_apellidos` `apellidos`,
    `socia`.`soc_email` `email`,
    `socia`.`soc_web` `sitio_web`,
    `socia`.`soc_metodo_pago` `metodo_pago`,
    `socia`.`soc_dir` `dir`,
    `socia`.`soc_cp` `cp`,
    `socia`.`soc_poblacion` `poblacion`,
    `socia`.`soc_provincia` `provincia`,
    `socia`.`soc_pais` `pais`,
    `socia`.`soc_es_autonoma` `es_autonoma`,
    `socia`.`soc_tlf` `tlf`,
    `socia`.`soc_movil` `movil`,
    `socia`.`soc_fax` `fax`,
    `socia`.`soc_iban` `iban`,
    `socia`.`soc_cuota` `cuota`,
    `socia`.`soc_fact_nombre` `fact_nombre`,
    `socia`.`soc_fact_nif` `fact_nif`,
    `socia`.`soc_fact_dir` `fact_dir`,
    `socia`.`soc_fact_cp` `fact_cp`,
    `socia`.`soc_fact_poblacion` `fact_poblacion`,
    `socia`.`soc_fact_provincia` `fact_provincia`,
    `socia`.`soc_fact_pais` `fact_pais`,
    `socia`.`soc_referida_por` `referida_por`,
    `socia`.`soc_notas` `notas`,
(SELECT MAX(`baja`.`baja_fecha`) FROM `baja` WHERE `baja`.`baja_socia` = codsocia) as `fecha_baja`,
(SELECT MAX(`alta`.`alta_fecha`) FROM `alta` WHERE `alta`.`alta_socia` = codsocia) as `fecha_alta`
FROM
`socia`
LEFT JOIN `alta` ON `alta`.`alta_socia` = `socia`.`soc_cod`
LEFT JOIN `baja` ON `baja`.`baja_socia` = `socia`.`soc_cod`
WHERE
`socia`.`soc_cod` = codsocia
ORDER BY `alta`.`alta_fecha` DESC, `baja`.`baja_fecha` DESC
LIMIT 1$$

DROP PROCEDURE IF EXISTS `consultar_socias_empresa`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `consultar_socias_empresa` (IN `cif` VARCHAR(20))   SELECT
    `arame`.`socia`.`soc_cod` AS `cod`,
    `arame`.`socia`.`soc_nif` AS `nif`,
    `arame`.`socia`.`soc_alta` AS `alta`,
    `arame`.`socia`.`soc_nombre` AS `nombre`,
    `arame`.`socia`.`soc_apellidos` AS `apellidos`
FROM
    
        `arame`.`socia`
    LEFT JOIN `socias_pertenecen_empresas` ON
        
            `socia`.`soc_cod`=`socias_pertenecen_empresas`.`sociasoc_cod`
        
    
    WHERE `socias_pertenecen_empresas`.`Empresaemp_cif`=cif$$

DROP PROCEDURE IF EXISTS `obtener_email_socia`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `obtener_email_socia` (IN `codigo` INT(5))   SELECT `socia`.`soc_email` FROM `socia` WHERE `socia`.`soc_cod` = codigo$$

DROP PROCEDURE IF EXISTS `ver_socias_activas_semestre`$$
CREATE DEFINER=`arame_db_admin`@`%` PROCEDURE `ver_socias_activas_semestre` (IN `semestre` VARCHAR(5), IN `year` INT)   BEGIN
DECLARE start_date DATE;
DECLARE end_date DATE;

IF semestre = '1S' THEN
    SET start_date = CONCAT(year, '-01-01');
    SET end_date = CONCAT(year, '-06-30');
ELSEIF semestre = '2S' THEN
    SET start_date = CONCAT(year, '-07-01');
    SET end_date = CONCAT(year, '-12-31');
END IF;

SELECT
    `arame`.`socia`.`soc_cod` AS `soc_cod`,
    `arame`.`socia`.`soc_nif` AS `soc_nif`,
    `arame`.`socia`.`soc_alta` AS `soc_alta`,
    `arame`.`socia`.`soc_nombre` AS `soc_nombre`,
    `arame`.`socia`.`soc_apellidos` AS `soc_apellidos`,
    `arame`.`socia`.`soc_email` AS `soc_email`,
    `arame`.`socia`.`soc_metodo_pago` AS `soc_metodo_pago`,
    `arame`.`socia`.`soc_dir` AS `soc_dir`,
    `arame`.`socia`.`soc_cp` AS `soc_cp`,
    `arame`.`socia`.`soc_poblacion` AS `soc_poblacion`,
    `arame`.`socia`.`soc_provincia` AS `soc_provincia`,
    `arame`.`socia`.`soc_es_autonoma` AS `soc_es_autonoma`,
    `arame`.`socia`.`soc_tlf` AS `soc_tlf`,
    `arame`.`socia`.`soc_movil` AS `soc_movil`,
    `arame`.`socia`.`soc_fax` AS `soc_fax`,
    `arame`.`socia`.`soc_iban` AS `soc_iban`,
    `arame`.`socia`.`soc_cuota` AS `soc_cuota`,
    `arame`.`baja`.`baja_fecha` AS `fecha_baja`
FROM
    `socia`
LEFT JOIN
    `baja` ON `socia`.`soc_cod` = `baja`.`baja_socia`
WHERE
    `baja`.`baja_fecha` BETWEEN start_date AND end_date
OR
    `socia`.`soc_alta` = 1;
END$$

DELIMITER ;

DROP TABLE IF EXISTS `alta`;
CREATE TABLE IF NOT EXISTS `alta` (
  `alta_fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `alta_socia` int(5) NOT NULL,
  PRIMARY KEY (`alta_fecha`,`alta_socia`),
  KEY `FKAlta936803` (`alta_socia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `alta` (`alta_fecha`, `alta_socia`) VALUES
('0000-00-00 00:00:00', 1),
('0000-00-00 00:00:00', 2),
('0000-00-00 00:00:00', 3),
('0000-00-00 00:00:00', 4),
('0000-00-00 00:00:00', 5),
('0000-00-00 00:00:00', 6),
('0000-00-00 00:00:00', 7),
('0000-00-00 00:00:00', 8),
('0000-00-00 00:00:00', 9),
('0000-00-00 00:00:00', 10),
('0000-00-00 00:00:00', 11),
('0000-00-00 00:00:00', 12),
('0000-00-00 00:00:00', 13),
('0000-00-00 00:00:00', 14),
('0000-00-00 00:00:00', 15),
('0000-00-00 00:00:00', 16),
('0000-00-00 00:00:00', 17),
('0000-00-00 00:00:00', 18),
('0000-00-00 00:00:00', 19),
('0000-00-00 00:00:00', 20),
('0000-00-00 00:00:00', 21),
('0000-00-00 00:00:00', 23),
('0000-00-00 00:00:00', 24),
('0000-00-00 00:00:00', 25),
('0000-00-00 00:00:00', 35),
('0000-00-00 00:00:00', 37),
('0000-00-00 00:00:00', 38),
('0000-00-00 00:00:00', 39),
('0000-00-00 00:00:00', 114),
('0000-00-00 00:00:00', 115),
('0000-00-00 00:00:00', 123),
('0000-00-00 00:00:00', 139),
('2004-04-30 22:00:00', 36),
('2009-12-31 23:00:00', 30),
('2011-12-31 23:00:00', 26),
('2011-12-31 23:00:00', 27),
('2012-02-29 23:00:00', 28),
('2012-10-31 23:00:00', 29),
('2013-02-28 23:00:00', 34),
('2013-10-31 23:00:00', 31),
('2013-12-31 23:00:00', 32),
('2013-12-31 23:00:00', 33),
('2015-06-30 22:00:00', 40),
('2015-12-31 23:00:00', 41),
('2015-12-31 23:00:00', 44),
('2016-01-17 23:00:00', 42),
('2016-03-16 23:00:00', 43),
('2016-04-03 22:00:00', 45),
('2016-06-02 22:00:00', 46),
('2016-07-11 22:00:00', 48),
('2016-07-25 22:00:00', 47),
('2016-09-25 22:00:00', 49),
('2016-09-29 22:00:00', 50),
('2016-10-19 22:00:00', 51),
('2016-11-06 23:00:00', 52),
('2016-11-06 23:00:00', 53),
('2016-11-21 23:00:00', 54),
('2016-11-28 23:00:00', 55),
('2016-12-31 23:00:00', 22),
('2016-12-31 23:00:00', 56),
('2016-12-31 23:00:00', 57),
('2016-12-31 23:00:00', 58),
('2016-12-31 23:00:00', 129),
('2017-02-23 23:00:00', 59),
('2017-02-28 23:00:00', 60),
('2017-03-08 23:00:00', 61),
('2017-04-16 22:00:00', 62),
('2017-06-26 22:00:00', 63),
('2017-12-31 23:00:00', 64),
('2018-02-12 23:00:00', 65),
('2018-02-14 23:00:00', 66),
('2018-05-03 22:00:00', 67),
('2018-05-06 22:00:00', 68),
('2018-06-05 22:00:00', 69),
('2018-06-27 22:00:00', 70),
('2018-07-31 22:00:00', 71),
('2018-08-31 22:00:00', 72),
('2018-09-02 22:00:00', 73),
('2018-10-15 22:00:00', 74),
('2018-10-16 22:00:00', 75),
('2018-11-14 23:00:00', 76),
('2019-01-01 23:00:00', 77),
('2019-01-01 23:00:00', 78),
('2019-01-01 23:00:00', 79),
('2019-01-28 23:00:00', 80),
('2019-02-12 23:00:00', 81),
('2019-03-06 23:00:00', 82),
('2019-03-06 23:00:00', 83),
('2019-06-30 22:00:00', 84),
('2019-07-17 22:00:00', 85),
('2019-07-21 22:00:00', 86),
('2019-07-23 22:00:00', 87),
('2019-09-08 22:00:00', 88),
('2019-10-07 22:00:00', 89),
('2019-10-07 22:00:00', 90),
('2019-10-31 23:00:00', 91),
('2019-10-31 23:00:00', 92),
('2019-10-31 23:00:00', 93),
('2019-11-07 23:00:00', 94),
('2019-11-12 23:00:00', 95),
('2019-11-13 23:00:00', 96),
('2019-11-24 23:00:00', 97),
('2019-12-12 23:00:00', 98),
('2019-12-31 23:00:00', 108),
('2020-01-06 23:00:00', 99),
('2020-01-15 23:00:00', 100),
('2020-02-03 23:00:00', 101),
('2020-02-12 23:00:00', 102),
('2020-02-19 23:00:00', 103),
('2020-02-26 23:00:00', 104),
('2020-03-06 23:00:00', 106),
('2020-03-09 23:00:00', 105),
('2020-03-14 23:00:00', 107),
('2020-05-31 22:00:00', 109),
('2020-06-21 22:00:00', 110),
('2020-07-04 22:00:00', 111),
('2020-08-31 22:00:00', 112),
('2020-09-23 22:00:00', 113),
('2020-11-05 23:00:00', 116),
('2020-11-05 23:00:00', 117),
('2020-11-23 23:00:00', 118),
('2020-12-14 23:00:00', 119),
('2021-02-07 23:00:00', 120),
('2021-02-09 23:00:00', 121),
('2021-03-12 23:00:00', 123),
('2021-03-15 23:00:00', 122),
('2021-03-17 23:00:00', 124),
('2021-03-31 22:00:00', 125),
('2021-04-20 22:00:00', 126),
('2021-05-03 22:00:00', 127),
('2021-05-17 22:00:00', 128),
('2021-06-23 22:00:00', 130),
('2021-06-29 22:00:00', 131),
('2021-07-07 22:00:00', 132),
('2021-07-26 22:00:00', 133),
('2021-07-27 22:00:00', 134),
('2021-09-08 22:00:00', 135),
('2021-09-13 22:00:00', 136),
('2021-11-04 23:00:00', 137),
('2021-11-04 23:00:00', 138),
('2021-11-04 23:00:00', 140),
('2021-11-04 23:00:00', 141),
('2021-11-18 23:00:00', 142),
('2021-12-18 23:00:00', 143),
('2021-12-28 23:00:00', 144),
('2022-02-17 23:00:00', 145),
('2022-02-24 23:00:00', 146),
('2022-02-24 23:00:00', 147),
('2022-02-24 23:00:00', 148),
('2022-04-10 22:00:00', 149),
('2022-04-10 22:00:00', 150),
('2022-04-27 22:00:00', 152),
('2022-05-02 22:00:00', 151),
('2022-05-29 22:00:00', 153),
('2022-06-05 22:00:00', 154),
('2022-06-09 22:00:00', 155),
('2022-06-15 22:00:00', 156),
('2022-06-25 22:00:00', 157),
('2022-06-30 22:00:00', 158),
('2022-07-06 22:00:00', 159),
('2022-08-31 22:00:00', 160),
('2022-08-31 22:00:00', 161),
('2022-09-27 22:00:00', 162),
('2022-10-18 22:00:00', 165),
('2022-10-18 22:00:00', 166),
('2022-10-24 22:00:00', 163),
('2022-10-24 22:00:00', 164),
('2022-11-16 23:00:00', 167),
('2022-11-22 23:00:00', 168),
('2022-11-23 23:00:00', 169),
('2022-11-24 23:00:00', 170),
('2023-02-10 11:48:30', 171),
('2023-02-10 11:58:23', 172),
('2023-02-17 11:18:39', 173),
('2023-02-21 07:27:23', 174),
('2023-02-21 15:03:35', 175);

DROP TABLE IF EXISTS `baja`;
CREATE TABLE IF NOT EXISTS `baja` (
  `baja_fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `baja_socia` int(5) NOT NULL,
  PRIMARY KEY (`baja_fecha`,`baja_socia`),
  KEY `FKBaja631381` (`baja_socia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `baja` (`baja_fecha`, `baja_socia`) VALUES
('0000-00-00 00:00:00', 24),
('0000-00-00 00:00:00', 35),
('2021-11-03 23:00:00', 54),
('2021-11-14 23:00:00', 101),
('2021-12-09 23:00:00', 99),
('2022-01-26 23:00:00', 10),
('2022-01-26 23:00:00', 52),
('2022-01-26 23:00:00', 84),
('2022-01-26 23:00:00', 129),
('2022-01-27 23:00:00', 32),
('2022-10-06 22:00:00', 107),
('2022-11-14 23:00:00', 37),
('2022-11-14 23:00:00', 80),
('2022-12-20 23:00:00', 49),
('2022-12-27 23:00:00', 60),
('2022-12-28 23:00:00', 38),
('2023-01-11 23:00:00', 1);
DROP VIEW IF EXISTS `consultar_correos_socias`;
CREATE TABLE IF NOT EXISTS `consultar_correos_socias` (
`email` varchar(80)
,`socia` int(5)
);
DROP VIEW IF EXISTS `consultar_empresas`;
CREATE TABLE IF NOT EXISTS `consultar_empresas` (
`nif` varchar(10)
,`nombre` varchar(100)
,`dir` varchar(200)
,`cp` int(5)
,`poblacion` varchar(100)
,`provincia` varchar(50)
,`pais` varchar(50)
,`iban` varchar(24)
,`email` varchar(80)
,`telefono` varchar(13)
,`telefono_2` varchar(13)
,`fax` varchar(13)
,`num_trabajadores` int(5)
,`year_fundacion` int(4)
,`descripcion` varchar(1000)
,`es_autonoma` tinyint(1)
,`notas` varchar(1000)
);
DROP VIEW IF EXISTS `consultar_sectores`;
CREATE TABLE IF NOT EXISTS `consultar_sectores` (
`nombre` varchar(100)
);
DROP VIEW IF EXISTS `consultar_socias`;
CREATE TABLE IF NOT EXISTS `consultar_socias` (
`cod` int(5)
,`nif` varchar(10)
,`alta` tinyint(1)
,`nombre` varchar(50)
,`apellidos` varchar(100)
,`email` varchar(80)
,`metodo_pago` varchar(50)
,`dir` varchar(200)
,`cp` int(5)
,`poblacion` varchar(100)
,`provincia` varchar(50)
,`pais` varchar(50)
,`es_autonoma` tinyint(1)
,`tlf` varchar(14)
,`movil` varchar(14)
,`fax` varchar(14)
,`iban` varchar(24)
,`cuota` varchar(100)
,`fact_nombre` varchar(100)
,`fact_dir` varchar(200)
,`fact_cp` int(5)
,`fact_poblacion` varchar(100)
,`fact_provincia` varchar(50)
,`fact_pais` varchar(50)
,`cuota_cuantia` double
,`notas` varchar(1000)
);
DROP VIEW IF EXISTS `consultar_years_recibos`;
CREATE TABLE IF NOT EXISTS `consultar_years_recibos` (
`year` int(4)
);

DROP TABLE IF EXISTS `cuota`;
CREATE TABLE IF NOT EXISTS `cuota` (
  `cuota_nombre` varchar(100) NOT NULL,
  `cuota_cuantia` double NOT NULL,
  PRIMARY KEY (`cuota_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `cuota` (`cuota_nombre`, `cuota_cuantia`) VALUES
('Exenta', 0),
('Normal', 40),
('Reducida', 20);

DROP TABLE IF EXISTS `empresa`;
CREATE TABLE IF NOT EXISTS `empresa` (
  `emp_cif` varchar(10) NOT NULL,
  `emp_nombre` varchar(100) NOT NULL,
  `emp_dir` varchar(200) NOT NULL,
  `emp_cp` int(5) NOT NULL,
  `emp_poblacion` varchar(100) NOT NULL,
  `emp_provincia` varchar(50) NOT NULL,
  `emp_pais` varchar(50) NOT NULL,
  `emp_iban` varchar(24) DEFAULT '',
  `emp_email` varchar(80) DEFAULT '',
  `emp_web` varchar(100) DEFAULT '',
  `emp_tlf` varchar(13) DEFAULT '',
  `emp_tlf_2` varchar(13) DEFAULT '',
  `emp_fax` varchar(13) DEFAULT '',
  `emp_num_trabajadores` int(5) DEFAULT NULL,
  `emp_year_fundacion` int(4) DEFAULT NULL,
  `emp_descripcion` varchar(1000) DEFAULT '',
  `emp_es_autonoma` tinyint(1) NOT NULL DEFAULT 0,
  `emp_notas` varchar(1000) DEFAULT '',
  PRIMARY KEY (`emp_cif`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `empresa` (`emp_cif`, `emp_nombre`, `emp_dir`, `emp_cp`, `emp_poblacion`, `emp_provincia`, `emp_pais`, `emp_iban`, `emp_email`, `emp_web`, `emp_tlf`, `emp_tlf_2`, `emp_fax`, `emp_num_trabajadores`, `emp_year_fundacion`, `emp_descripcion`, `emp_es_autonoma`, `emp_notas`) VALUES
('16013271G', 'INTERLINK', 'Breton 11, 6 Centro', 50005, 'Zaragoza', 'Zaragoza', 'España', 'ES3720389951713000103733', 't.santafe@interlink-idiomas.com', 'http://www.interlink-idiomas.com/', '976569358', '', '', 0, NULL, '', 1, ''),
('16802548J', 'Integral Growth Consulting', 'Paseo Independencia, 8', 50009, 'Zaragoza', 'Zaragoza', 'España', 'ES5621032790390010002135', 'delcastillo.maria@gmail.com', 'http://www.igrconsulting.com/', '', '', '', 0, NULL, '', 1, ''),
('17220134B', 'ARIZA ABOGADOS', 'C/ Conde de Aranda, nº 1, 3º dcha', 0, 'Zaragoza', 'Zaragoza', 'España', 'ES1100492833972615981995', 'marimarmartinez@arizaabogados.com', 'www.arizaabogados.com', '976444200', '', '', 5, NULL, '', 1, ''),
('17719934K', 'Arellano y Felipe Arqtos', 'Mariano Royo 9-11 5º-6º', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES5431835000801014903528', 'mfelipecaparros@gmail.com', '', '', '', '', 0, NULL, '', 1, ''),
('17721834N', 'GABINETE PSICOLOGICO CRISTINA EQUIZA LÓPEZ', 'Avda. Cesareo Alierta, 16, escal 4, 9º dcha', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES5720855234850330938400', 'cristinaequiza@equiza.es', 'www.cristinaequiza.es', '976227050', '', '', 1, NULL, '', 1, ''),
('17729629X', 'CRECIMIENTO COMO ACTITUD', 'C/ Jerónimo Zurita 15 1º D', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES84/0128040069010011765', 'mariangil@mariangil.com', 'http://www.crecimientocomoactitud.com/', '', '', '', 0, NULL, '', 1, ''),
('17731994Y', 'SOLER LIMPIEZAS', 'CALLE MARQUÉS DE AHUMADA, 1-3 4ºT', 50007, 'Zaragoza', 'Zaragoza', 'España', 'ES3200495485532316289856', 'susanapardos@gmail.com', '', '', '', '', 0, NULL, '', 1, ''),
('17733170D', 'RG', 'Paseo de la Mina 7', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES9020858242100330180854', 'reyes@rgmarketing.es', 'http://rgmarketing.es', '', '', '', 0, NULL, '', 1, ''),
('17736691B', 'Nati Hueso', 'c/Mayoral 4, 6d', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES4201280400640100208963', 'natihueso@natihueso.com ', '', '', '', '', 1, NULL, '', 1, ''),
('17739481H', 'PELUQUERÍA MARIBEL CROS', 'Gertrudis Gómes Avellaneda 57 Local C Peluquería', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES6030350314793140015462', 'peluqueriamaribelcros@hotmail.com', 'https://www.peluqueriamaribelcros.com/', '876283242', '', '', 0, NULL, '', 1, ''),
('17741619V', 'Ana María Marco Salvador', 'Torrenueva 31', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES0421005449140200005209', 'anamariamarco@reicaz.com', '', '976901037', '', '', 0, NULL, '', 1, ''),
('17749505Z', 'THE MODERN CULTURAL PRODUCTIONS', 'Espoz y Mina 4', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES6100810170190002152420', 'anarevilla@themoderncultural.com', '', '', '', '', 0, NULL, '', 1, ''),
('17749624H', 'PROJECT TEMPUS SERVICIOS INTEGRALES S.L ', 'Av. Pablo Ruiz Picasso4, local', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES6100811146750001023206', 'ed4.project@gmail.com', 'http://www.muninteriorismo.com', '876285418', '', '', 25, NULL, '', 1, ''),
('17862956Y', 'GRUPO MONTANER ASOCIADOS', 'CALLE JERÓNIMO ZURITA Nº8, 1º', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES9120850138380330274264', 'pmartin@grupmontaner.com', 'https://www.grupmontaner.com/', '976158092', '', '', 0, NULL, '', 1, ''),
('18019709Z', 'JULIAN ABOGADOS', 'San Clemente 25 4ª Puerta', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES1300817220130001146619', 'bjulian@abogadosjulian.com', 'www.abogadosjulian.com', '976483507', '', '', 2, NULL, '', 1, ' www.mediacionparatuempresa.com'),
('18209654W', 'MAPFRE ESPAÑA', 'Plaza Emperador Carlos V', 50009, 'Zaragoza', 'Zaragoza', 'España', 'ES2120384500233000446532', 'ieguil@mapfre.com', '', '', '', '', 0, NULL, '', 1, ''),
('18418296B', 'FUNDACION BODAS DE ISABEL', 'Plaza Catedral 9 Bajo', 44001, 'Teruel ', 'Teruel', 'España', 'ES8230800001862063662718', 'raquel@bodasdeisabel.com', 'www.bodasdeisabel.com', '978618504', '', '', 4, NULL, '', 1, ''),
('18424762Z', 'B the travel brand & Catai', 'San Clemtente 15', 50001, 'Zaragoza', 'Zaragoza', 'España', 'TRANSFERENCIA', 'p.punter@bthetravelbrand.com', ' http://premium.bthetravelbrand.com', '876258456', '', '', 4, NULL, '', 1, ''),
('18440260X', 'PSICOLOGA', 'Calle Mariana Pineda 16 3ºD', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES6014650100991713207611', 'paguerri@boogaloovegetal.com', '', '', '', '', 0, NULL, '', 1, ''),
('18457121N', 'LETSTAT', 'Plaza Justicia 12, 1ºB', 50650, 'Gallur', 'Zaragoza', 'España', 'ES3920853954410330280872', 'lsanchezalvarez7@gmail.com', 'https://www.letstat.es/', '', '', '', 0, NULL, '', 1, ''),
('18935660J', 'MARA VRIL', 'Paseo de la Mina 1, Entresuelo', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES7601280400640100165962', 'teresamaria@teresamaria.es', 'www.maravril.es', '876704381', '', '', 0, NULL, '', 1, ''),
('25135411E', 'TÍTULO PARTICULAR / COLEGIO OFICIAL DE ECONOMISTAS DE ARAGÓN', 'C/ Don Jaime I, 16', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES7720855204670333239322', 'angeles.lopez@economistas.org', 'www.ecoara.org', '976281356', '', '', 5, NULL, '', 1, ''),
('25145357D', 'ESTUDIO RUISEÑORES', 'Calle Juan Pablo Bonet, 25 ', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES6620850172540330258262', 'estudio.ruisenores@hotmail.com', '', '976021187', '', '', 0, NULL, '', 1, ''),
('25147473D', 'Directora General de Promoción Agroalimentaria del Gobierno de Aragón ', 'Avda. Madrid, 11-13, 2º E', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES2500495480202916240729', 'carmenurbanogomez@gmail.com', '', '976794371', '', '976794361', 2, NULL, '', 1, ''),
('25152771V', 'PARAVIVIR INMOBILARIA', 'C/ Méndez Núñez 23, local', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES8400811986540001304540', 'cristina@paravivir.es', 'http://www.paravivir.es', '876043951', '', '', 0, NULL, '', 1, ''),
('25161190H', 'LUCEA VALERO', 'Torrecilla de Valmadrid, Casa 11. Urb. Nidalia', 50420, 'Cadrete', 'Zaragoza', 'España', 'ES6614650230471714193533', 'info@luceavalero.com', 'www.luceavalero.com', '', '', '', 1, NULL, '', 1, ''),
('25168295Q', 'TRIFOLIUM', 'C/ Silveria Fañanas, 57, local', 50011, 'Zaragoza', 'Zaragoza', 'España', 'ES5700810170190001642369', 'aruizpinilla@gmail.com', 'www.trifoliumca.com', '976403940', '', '976332362', 1, NULL, '', 1, 'aruiz@trifoliumca.com'),
('25170886P', 'Virtus comunicación', '', 0, 'Zaragoza', 'Zaragoza', 'España', 'ES4921009096552200114561', 'mercedes@virtuscomunicacion.com', 'https://www.virtuscomunicacion.com/', '', '', '', 0, NULL, '', 1, ''),
('25176371L', 'Ana Miranda Estudio', 'Don Jaime I, nº14, 3ºcentro', 50001, 'Zaragoza', 'Zaragoza', 'España', '', 'info@anamirandaestudio.com', 'https://linktr.ee/AMirandaestudio', '', '', '', 1, NULL, '', 1, ''),
('25190856Z', 'Raquel Serrano', 'Schleissheimerstrasse 232a, Muchich, Alemania', 80797, 'Munich', 'Munich', 'Alemania', 'DE32200411550576536700', 'raquel.serrano@gmail.com', 'https://www.raquelserrano.me/   ', '+491762434089', '', '', 1, NULL, '', 1, ''),
('25427793M', 'BM Regalos de Empresa', 'Poligono Empresarium, calle retama 25 nave B7', 50720, 'Zaragoza', 'Zaragoza', 'España', 'ES4720850157340330327167', 'bm@bmregalosdeempresa.com', 'http://www.bmregalosdeempresa.com', '876262641', '', '', 4, NULL, '', 1, ''),
('25435081W', 'Landa Propiedaes', 'Avda Gómez Laguna, 19. 11B', 50009, 'Zaragoza', 'Zaragoza', 'España', 'ES0231910028335633960124', 'carroyo@landainmobiliaria.com', 'http://www. landapropiedades.com', '976301480', '', '', 26, NULL, '', 1, ''),
('25437440S', 'CENTRO DE NEGOCIOS ZOSE', 'Avenida Cesareo Alierta 23-25, Piso 1 Puerta 104', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES3631910133176225772125', 'carmen@adostorres.com', 'http://apartamentosdostorres.com/', '', '', '', 0, NULL, '', 1, ''),
('25456293P', 'STPEUROPA', 'Paseo Independencia 22 7ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES8020858244040330215566', 'mmunoz@stpeuropa.eu', 'http://www.stpeuropa.eu/', '', '', '', 0, NULL, '', 1, ''),
('25458719C', 'ARTE POR CUATRO', 'C/ Sevilla, 21, escal dcha, 5ºC', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES1100650123170001045237', 'info@arteporcuatro.com', 'www.arteporcuatro.com', '', '', '', 1, NULL, '', 1, ''),
('25460196R', 'IMAGINA-T', 'Avenida Cesareo Alierta 9, 8ª 1ª', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES2620855215870331323348', 'redaccion@conpequesenzgz.com', 'https://imagina-t.net/', '976299202', '', '', 0, NULL, '', 1, ''),
('254663468F', 'RUTH BARRANCO INTERIORISMO', 'C/ Camino de las Torres 8, esc. 1, despacho 10 A', 50002, 'Zaragoza', 'Zaragoza', 'España', 'ES5720855230120331415440', 'ruthbarrancointeriorismo@gmail.com', '', '', '', '', 0, NULL, '', 1, ''),
('25473145R', 'CLIC BATERÍAS', 'C/ Brazal Valseca, 5, bajos', 50016, 'Zaragoza', 'ZaragozA', 'España', 'ES1021001783500200107485', 'clicbaterias@gmail.com', 'www.clicbaterias.es', '647450175', '', '976573119', 1, NULL, '', 1, 'info@clicbaterias.es'),
('29085296W', 'SUSANA USIETO DECO ', 'Brazato 1, local', 50012, 'Zaragoza', 'Zaragoza', 'España', 'TRANSFERENCIA', 'susanausietodeco@gmail.com ', '', '', '', '', 1, NULL, '', 1, ''),
('29097971G', 'ESCUELA COMUNICANDO', 'C/ Cervantes, 32', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES6901820745620201606133', 'slacruz@escuelacomunicando.es', '', '976213987', '', '', 0, NULL, '', 1, ''),
('29104356H', 'CALA DOC PRODUCCIONES', 'Calle Elche 8, 1ªA', 50002, 'Zaragoza', 'Zaragoza', 'España', 'ES3820855208410332584796', 'vickycalavia@gmail.com', 'www.vickycalavia.com', '', '', '', 1, NULL, '', 1, ''),
('29123690D', 'PILAR ARAGÜÉS FUMANAL', 'Avenida Ciudad de Soria 8 (PL3 La Terminal)', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES3420855255730330658992', 'soluciones@pilararagues.es', '', '', '', '', 0, NULL, '', 1, ''),
('29125397Z', 'María Pía Pablos Abiol', 'Zurita 17, Pral Izda', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES1301826900200201991313', 'mariajose.moliner@emeglobalbusiness.com', 'http://www.biointeriors.com', '976427022', '', '', 1, NULL, '', 1, ''),
('29127244K', 'SMARA DAVILA BALLESTEROS', 'Johann Sebastian Bach nº 35 3º A', 50012, 'Zaragoza', 'Zaragoza', 'España', 'ES1221002431010200329553', 'bertarecaj@almenaracomunicacion.com ', 'https://smaradavila.com/', '', '', '', 0, NULL, '', 1, ''),
('33451648B', 'BANCO SANTANDER, S.A.', '', 0, 'Zaragoza', 'Zaragoza', 'España', 'TRANSFERENCIA', 'gebonet@gruposantander.es', 'http://www.santander.es/', '', '', '', 0, NULL, '', 1, ''),
('33487992S', 'OPTICA BAJO ARAGON', 'Calle La Fuente 19', 44500, 'Andorra', 'Teruel', 'España', 'ES3500492313612614296016', 'carmenhurtado@live.com', 'http://opticabajoaragon.com/', '978842004', '', '', 0, NULL, '', 1, ''),
('48815021M', 'EDA Project', 'Calle Rafael Gasset, Nº 23. Tercero', 22800, 'Ayerbe', 'Huesca', 'España', 'TRANSFERENCIA', 'edaproject.irc@gmail.com', 'http://www.edaproject.es', '', '', '', 1, NULL, '', 1, ''),
('50444522W', 'App Saludenconductores sl', 'Doctor Casas nº20, 2ºD', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES6300815543540001370247', 'ameliacantarerogarcia@gmail.com', '', '976234128', '', '', 0, NULL, '', 1, ''),
('72888018Y', 'Miriam Chueca', 'C/ Emilio Alfaro Lapuerta, 1, 1º primera', 50017, 'Zaragoza', 'Zaragoza', 'España', 'ES3230170552572409826829', 'hola@miriamchueca.es', 'miriamchueca.es', '', '', '', 1, NULL, '', 1, ''),
('72978948V', 'EL TOCADOR DE ELENA', 'Federico Mayo 10', 44769, 'Utrillas', 'Teruel', 'España', 'ES1720854061740330078361', 'elenapolobeautycoach@gmail.com', 'https://www.el-tocador-de-elena.es/', '', '', '', 0, NULL, '', 1, ''),
('72997813E', 'Esther Canales Branding Studio', 'C/ San Antonio María Claret 13, 1H', 50005, 'Zaragoza', 'Zaragoza', 'España', '', 'info@esthercanales.com', '', '600681677', '', '', 0, 0, '', 1, ''),
('73022495W', 'Ana Lazaro', 'Calle Mayor 105', 50360, 'Daroca', 'Zaragoza', 'España', 'ES8400495527402716033187', 'holaanalazaro@gmail.com', 'https://www.linkedin.com/in/analazarocommunitymanager/', '', '', '', 1, NULL, '', 1, ''),
('73076956E', 'SERVILOG', 'C/Río Aragón 35 M', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', 'ES3820800665593041006560', 'mjoseg50@gmail.com', 'www.serviloga.com', '976108096', '', '', 1, NULL, '', 1, ''),
('73078869A', 'Perfumeria Complementos Pilar Garcés ', 'Ildelfonso Manuel Gil 25', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES4501826925150201518991', 'pilargarcespipa@gmail.com', 'http://www.pilargarcescomplementos.com/', '976510778', '', '', 0, NULL, '', 1, ''),
('73089407F', 'Andrea Lacueva Laborda ', 'ED. DR. JOAQUIN REPOLLES - PARQUE TECNOLÓGICO TECHNOPARK - OFICINA 6', 44600, 'Alcañiz', 'Teruel', 'España', 'ES4530800067982161531914', ' ofitecalacueva@gmail.com', '', '', '', '', 3, NULL, '', 1, ''),
('73089738Q', 'Fabricando Contenidos', 'Plaza San Miguel 1º 2ª', 44570, 'Calanda', 'Teruel', 'España', 'ES9130800011582384090318', 'isabel.conesa87@gmail.com', 'https://www.fabricandocontenidos.com/', '', '', '', 0, NULL, '', 1, ''),
('73104503S', 'STUDIO MOSTAZA', 'Marques de Lema 42', 44550, 'Alcorisa', 'Teruel', 'España', 'ES8800492315522414048700', 'studiomostaza@gmail.com', 'http://www.studiomostaza.com/', '', '', '', 0, NULL, '', 1, 'http://www.instagram.com/studio_mostaza'),
('73258281S', 'VISTEDECORATUCASA', 'P.T. TECHNOPARK, EDIF. DR. JOAQUIN REPOLLES ESPACIO COWORKING', 44600, 'Alcañiz', 'Teruel', 'España', 'ES5701820751520201579379', 'ana@vistedecoratucasa.com', 'http://www.vistedecoratucasa.com/', '', '', '', 0, NULL, '', 1, ''),
('73260844W', '3 IDEAS CONTADAS', 'C/ San Miguel 23, Dcha. 23', 44595, 'Valjunquera', 'Teruel', 'España', 'ES4330800008182479837821', 'redaccion@conpequesenzgz.com', 'http://www.3ideascontadas.com/', '', '', '', 0, NULL, '', 1, ''),
('76917174E', 'Agilmente', 'casa 17', 50540, 'Zaragoza', 'Borja', 'España', 'ES0320850131150330116208', 'informacion@somosagilmente.com', '', '976868616', '', '', 1, NULL, '', 1, ''),
('76921782F', 'Vilella y Asociados', 'Calle San Vicente Martir 11 escalera A, 2 izquierda', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES7300496992912110002128', 'pilar@vilellayasociados.com', ' https://www.vilellayasociados.com/', '', '', '', 1, NULL, '', 1, ''),
('A50007301', 'Agreda Automovil, S.A ', '', 0, '0', '0', 'España', '0', '', '', '', '', '', 0, NULL, '', 0, ''),
('A50013465', 'KALFRISA SA', 'PTR  Lopez Soriano, Crta Valmadrid', 50720, 'Zaragoza', 'Zaragoza', 'España', 'ES1821001645150100119529', 'sbeltran@kalfrisa.com', 'http://www.kalfrisa.com/', '976420731', '', '976471595', 65, NULL, '', 0, ''),
('A50051218', 'ARPA EQUIPOS MOVILES DE CAMPAÑA', 'Polígono Industrial Centrovía, Calle La Habana 25', 50198, 'La Muela', 'Zaragoza', 'España', 'CONFIRMING', 'clara@arpaemc.com', 'www.arpaemc.com', '976144770', '', '', 0, NULL, '', 0, ''),
('A50054313', 'ZAFORSA Zaragozana de Formularios SA', 'Pol. Ind. El Portazgo, nave 51/52', 50011, 'Zaragoza', 'Zaragoza', 'España', 'ES4200491824442310022376', 'bertalorente@zaforsa.es', 'www.zaforsa.com', '976322211', '', '', 48, NULL, '', 0, ''),
('A50070663', 'CONSTRUCCIONES RUBIO MORTE, S.A.', 'ALEJANDRO OLIVAN 20-22, LOCAL', 50011, 'Zaragoza', 'Zaragoza', 'España', 'ES3020855201210331102267', 'gema@rubiomorte.com', 'http://www.rubiomorte.com', '', '', '', 45, NULL, '', 0, ''),
('A50081546', 'DICSA', 'Pol. Industrial Alcalde Caballero C/ del Buen Acuerdo s/n', 50014, 'Zaragoza', 'Zaragoza', 'España', 'ES1701822407950000043846', 'dicsa@dicsaes.com', 'www.dicsaes.com', '976464100', '', '', 140, NULL, '', 0, ''),
('A50089812', 'DROLIMSA - DROGERÍA Y LIMPIEZA S.A.', 'Avenida Zaragoza 50', 50412, 'Cadrete', 'Zaragoza', 'España', 'ES0520855445770330365026', 'carmen@drolimsa.es', 'www.drolimsa.es', '976503197', '', '', 0, NULL, '', 0, ''),
('A50130277', 'INDUSTRIAS E DIAZ', 'Ctra. Castellon KM 6,2', 50720, 'La Cartuja Baja', 'Zaragoza', 'España', 'EFECTIVO', 'm.eugenia@industrias-ediaz.com', 'www.industrias-ediaz.com', '976454007', '', '', 70, NULL, '', 0, ''),
('A82473018', 'RENTA 4 BANCO', 'Paseo de la Habana 74', 28036, 'Madrid', 'Madrid', 'España', '', 'pbarcelona@renta4.es', 'http://r4.com', '976206093', '', '', 700, 1986, 'Banco de inversión especializado en gestión patrimonial, mercados de capitales y servicios de inversión.\r\nNuestra vocación es ofrecer a nuestros clientes la posibilidad de acceder a las mejores oportunidades de inversión.', 0, ''),
('A87456372', 'PHILYRA SA', 'C/ Méndez Coarasa', 50012, 'Zaragoza', 'Zaragoza', 'España', 'ES1600810170140001478549', 'pilar.muronavarro@gmail.com', '', '', '', '', 5, NULL, '', 0, ''),
('B09699505', 'SNAILSTEP', 'CEEIARAGON', 50018, 'Zaragoza', 'Zaragoza', 'España', '', 'fperez@snailstep.com', 'http://www.snailstep.com', '608048892', '', '', 1, 2022, 'Startup de base tecnológica, basada en el diseño de soluciones digitales para el cuidado de la salud y bienestar de las personas y la reactivación económica de las ciudades, en sintonía con los valores medioambientales, para mejorar las condiciones de las personas.', 0, ''),
('B09979865', 'Alcanalytics S.L.', '', 0, '', '', '', '', '', '', '', '', '', 3, NULL, '', 0, ''),
('B22146005', 'MESBUR S.L.', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B22210843', 'NEWLINK EDUCATION', 'Edificio Aida / Calle Madre Rafols 2, Planta 8 Oficina 4', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES5400490391132990252020', 'gemmabesperez@gmail.com', '', '', '', '', 0, NULL, '', 0, '25137749 Z'),
('B22337646', 'ABADIA DE SIETAMO SL', 'C/Alta 10', 22120, 'Sietamo', 'Huesca', 'España', 'ES2921002160720200193430', 'info@abadiasietamo.es', 'www.abadiasietamo.es', '', '', '', 0, NULL, '', 0, ''),
('B22383715', 'Hotel El privilegio', 'ZACALERA, 1', 22663, 'Tramacastilla de Tena', 'Huesca', 'España', 'ES6800811903320001023712', 'anabelcostas@elprivilegio.com', 'http://www.elprivilegio.com', '974487206', '', '', 10, NULL, '', 0, ''),
('B44201697', 'MILE ALCAÑIZ S.L.', 'Calle Baja 30', 44600, 'Alcañiz', 'Teruel', 'España', 'ES7830800008142058966124', 'milejardinhogar@gmail.com', '', '978830519', '', '', 0, NULL, '', 0, ''),
('B50032002', 'López Soriano', 'CARRETERA DE CASTELLÓN 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', 'ES2500610384110000530114', 'rosapalacin49@gmail.com ', 'http://www.grupoilssa.com/', '976415200', '', '', 0, NULL, '', 0, ''),
('B50056183', 'CEFOR IZQUIERDO', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B50061019', 'SEDOVIN', 'POLIGONO LA PUEBLA DE ALFINDEN CALLE K 29', 50171, 'La Puebla', 'Zaragoza', 'España', 'ES8921004616162200028867', 'mav@sedovin.com', 'http://sedovin.com/', '976109983', '', '', 0, NULL, '', 0, ''),
('B50107051', 'Biosalud DAY Hospital', 'Residencial Paraíso, 9', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES8321003386812200099997', 'anachico@biosalud.org', 'http://www.biosalud.org', '976221133', '', '', 9, NULL, '', 0, ''),
('B50152149', 'ANJUL INSTALACIONES S.L.', 'Poligono Molino del Pilar, Calle Rudolf Diesel Nave 27', 50015, 'Zaragoza', 'Zaragoza', 'España', 'ES0620850133040300152905', 'anjul@anjul.es', '', '976526345', '', '', 0, NULL, '', 0, ''),
('B50500263', 'UDESER', 'Mercazaragoza, local 2,', 50014, 'Zaragoza', 'Zaragoza', 'España', 'ES8800810362910001145720', 'isabel@udeser.com', '', '976471216', '', '976472747', 3, NULL, '', 0, ''),
('B50501097', 'LOS MAIZALES RESIDENCIAS GERIATRICAS', 'Poligono Argualas, Nave 52-B', 50012, 'Pinseque', 'Zaragoza', 'España', 'ES1200810363320001230827', 'losmaizales@losmaizales.com', 'www.losmaizales.com', '976656870', '', '', 70, NULL, '', 0, ''),
('B50541796', 'COMEX', 'P.I Malpica C/E Parcelas 32-39 Nave 6', 500016, 'Zaragoza', 'Zaragoza', 'España', 'ES8901280406300109147451', 'comex@tomasdetierra.com', 'http://www.tomasdetierra.com/', '', '', '', 0, NULL, '', 0, ''),
('B50566652', 'CENTRO DE NEGOCIOS LOS SITIOS', 'C/ Sanclemente 25 - 4º Derecha (Esquina Plaza de  Los Sitios)', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES3120385835106000558908', 'info@cnlossitios.com', 'www.cnlossitios.com', '976232100', '', '', 2, NULL, '', 0, ''),
('B50633759', 'GAMMA, SERVICIOS', 'C/ La Milagrosa 5-7, Local 9', 50009, 'Zaragoza', 'Zaragoza', 'España', 'ES4130800065162291741920', 'gamma@gammasl.com', 'www.gammaservicios.com', '976382115', '', '', 110, NULL, '', 0, ''),
('B50667690', 'GRIFERIAS GROBER', 'Calle Alaún 19. Pla-Za', 50198, 'Zaragoza', 'Zaragoza', 'España', 'ES3021005687720200004646', 'mladron@grb.es', 'http://www.grb.es/', '976504170', '', '', 0, NULL, '', 0, ''),
('B50670264', 'BIOKNOSTIC, S.L.', 'C/ Mendez Nuñez, 10, 1ºB', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES6100492833912116300827', 'mjlorente@bioknostic.com', 'http://www.bioknostic.com/', '976489332', '', '976535820', 3, NULL, '', 0, ''),
('B50670843', 'Manuel Larraga S.L.', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B50735802', 'DISARAGON', 'CTRA CASTELLON, KM 3800', 50013, 'Zaragoza', 'Zaragoza', 'España', 'ES2801823131400010303201', 'laura.fuentes@disaragon.es', 'http://Disaragon.es', '976590313', '', '', 45, NULL, '', 0, ''),
('B50766930', 'SIMON ASESORES', 'C/ Alberto Duce 14 Bajo', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES8900811522950001161323', 'pilar@simonasesores.es', 'www.simonasesores.es', '976742226', '', '', 4, NULL, '', 0, ''),
('B50776947', 'IMPLASER', 'Polígono Borao Norte Nave 5A - B - C', 50172, 'Alfajarín', 'Zaragoza', 'España', 'ES3520855470620330232051', 'yarnal@implaser.com', 'www.implaser.com', '976455088', '', '', 47, NULL, '', 0, ''),
('B50792688', 'PUENTECRISTO SEGUROS SL', 'Fueros de Aragón 24', 50500, 'Tarazona', 'Zaragoza', 'España', 'ES7420800665563041001306', 'mgilgon@mapfre.com', '', '', '', '', 5, NULL, '', 0, ''),
('B50799790', 'IBERMAC ASESORES, S.L.', 'C/ San Jorge, 33, esc dcha, 1º Dcha', 50001, 'Zaragoza', 'Zaragoza', 'España', 'ES3600810170110001787087', 'blopez@ibermac.com', 'http://www.ibermac.com/', '976393593', '', '976204701', 2, NULL, '', 0, ''),
('B50856160', 'Servicios Integrales de Fincas de Aragón, S.L. (SIFA, S.L) ', 'C/ Angela Bravo Ortega, 19, bajos', 50011, 'Zaragoza', 'Zaragoza', 'España', 'TRANSFERENCIA', 'mescudero@gruposifu.com', 'http://www.gruposifu.com/', '', '', '', 0, NULL, '', 0, ''),
('B50906734', 'BODEGAS TEMPORE', 'CTRA. ZARAGOZA S/N', 50131, 'LECERA', 'Zaragoza', 'España', 'ES8801826900240201703811', 'p.yago@bodegastempore.com', 'http://www.bodegastempore.com', '976835040', '', '', 25, NULL, '', 0, ''),
('B50909423', 'AFIRIS ASESORES, S.L.', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B50919893', 'ESI SOLUCIONES TIC', 'Camino Mosquetera 45 Local', 50010, 'Zaragoza', 'Zaragoza', 'España', 'ES2700815583400001013106', 'marta@esisoluciones.es', 'www.esisoluciones.es', '976300140', '', '', 13, NULL, '', 0, ''),
('B50921295', 'Grupo Intelecto', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B50950518', 'MAS QUE DXT ARAGON', 'C/OVIEDO 5-7 LOCAL', 50007, 'Zaragoza', 'Zaragoza', 'España', 'ES4700495480262316148181', ' soniaguerra@sectorzaragoza.com', 'http://www.masquedxt.com', '976978890', '', '', 111, NULL, '', 0, ''),
('B50988906', 'Zeumat-Zesis', 'RODRIGO DIAZ DE VIVAR 6 LOCAL', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES3320850171040330124392', 'mrubio@zeumat.com', 'http://www.zeumat.com', '', '', '', 25, NULL, '', 0, ''),
('B65295560', 'SAN LEON ENERGY PLC', 'Paseo Independencia 24-26, 6º 2', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES0420855200820333403934', 'mpuig@sanleonenergy.com', 'www.sanleonenergy.com', '976974720', '', '', 0, NULL, '', 0, ''),
('B72946148', 'Jiménez Carbó Digital SL', 'Calle Cinco de Marzo 18, piso 2, oficina 1', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES2920850111720331415396', 'paula@jimenezcarbo.com', 'https://jimenezcarbo.com/', '976925310', '', '', 3, 2017, '', 0, ''),
('B86511722', 'KUCHEN KONZEPT / EME GLOBAL BUSINESS SL', 'Avenida San Jose 103', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES3801820740300201563404', 'mariajose.moliner@emeglobalbusiness.com', '', '867709685', '', '', 0, NULL, '', 0, ''),
('B93661726', 'NOVALUZ ENERGIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 'ES4201280790220100066973', 'patricia.raimundo@novaluz.es', 'www.novaluz.es', '', '', '', 0, NULL, '', 0, ''),
('B99050031', 'YOVOY ASESORES', 'C/ Lacarra de Miguel, nº 29, 2º', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES4401280401090100038136', 'asesoria@grupodancausa.com', 'www.yovoyasesores.com', '976230837', '', '976230837', 4, NULL, '', 0, ''),
('B99080772', 'TAISI S.L.', '', 0, '', '', '', '', '', '', '', '', '', 0, NULL, '', 0, ''),
('B99124588', 'INNOVA EVENTOS ZARAGOZA SL U', 'C/ Alfonso nº 17, planta 3ª Oficina 2', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES7921003847100200021978', 'natalia@innovazgz.com', 'www.innovazgz.com', '976233353', '', '', 3, NULL, '', 0, ''),
('B99137721', 'CENTRO OPTICO Y AUDITIVO VICENT', 'Calle Visconti 13', 50500, 'Tarazona', 'Zaragoza', 'España', 'ES8020851119510330338168', 'info@vicentevision.com', '', '976640931', '', '', 2, NULL, '', 0, ''),
('B99146458', 'YACANA ESTUDIOS Y SERVICIOS SL', 'Avda. Plabo Ruiz Picasso 16 - 2B', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES3001826925110201567375', 'mluz.martinez@economistas.org', 'www.ejecutivosenelcambio.es', '', '', '', 0, NULL, '', 0, ''),
('B99242406', 'ORGANIZACIÓN, COSTS Y GESTIÓN S.L.P ', 'C/ Bilbao 2, 3ºD', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES1120850103920331740284', 'yolanda.regalado@ocgcontroller.com', 'http://www.ocgcontroller.com/', '976232300', '', '', 0, NULL, '', 0, ''),
('B99254302', 'ZARAGOZA SERVICIOS', 'Calle Río Duero 13 Local', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES9800815583480001115420', 'nathalia@zaragozaservicios.es', 'www.zaragozaservicios.es', '876776400', '', '', 10, NULL, '', 0, ''),
('B99278160', 'Valero Bielsa abogados', 'Camino Cabaldos 60', 50013, 'Zaragoza', 'Zaragoza', 'España', '', 'sagrariovalero@reicaz.com', '', '976591905', '', '', 3, NULL, '', 0, ''),
('B99291528', 'Luz de Gestión y Medioambiente', '', 0, '', '', '', '', '', '', '', '', '', 4, NULL, '', 0, ''),
('B99320327', 'ALMENARA 1915', 'Camino Torrevillarroya 43', 50190, 'Zaragoza', 'Zaragoza', 'España', 'ES6330350310913100015033', 'bertarecaj@almenaracomunicacion.com ', '', '', '', '', 0, NULL, '', 0, ''),
('B99362972', 'BODEGAS RUBERTE HNOS.', 'Avda. de la Paz, 28', 50520, 'Magallón', 'Zaragoza', 'España', 'ES3500810578090001116418', 'susana@bodegasruberte.com', 'http://www.gruporuberte.com/legal.asp', '976858063', '', '976858475', 8, NULL, '', 0, ''),
('B99386584', 'ACADEMIA BEST WAY', 'C/ Carlos Oriz Garcia 7', 50011, 'Zaragoza', 'Zaragoza', 'España', 'ES5920385836416000692150', 'alba@academiabestway.com', 'http://www.academiabestway.com/', '', '', '', 0, NULL, '', 0, ''),
('B99391849', 'WORKING FORMACION', 'Paseo Rosales, 32, Local 9', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES8630350333703330010325', 'info@workingformacion.com', 'www.workingformacion.com', '976242109', '', '', 4, NULL, '', 0, ''),
('B99393704', 'ARTEYMERCHAN', 'C/ Bernardo Fita 21', 50005, 'Zaragoza', 'Zaragoza', 'España', 'ES5530350310913100009454', 'olga@arteymerchan.com', 'www.arteymerchan.com', '976974479', '', '', 2, NULL, '', 0, ''),
('B99407173', 'ARAGON INNOVA GESTIONA Y MAS SL.', 'C/ Clara Campoamor  16 3ª', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES7330800065182260834524', 'mgalardon@arainnova.com ', 'www.arainnova.com', '976519722', '', '', 3, NULL, '', 0, ''),
('B99413908', 'BATINSA', 'C/ Val. De Carbonera', 50162, 'Villamayor de Gállego', 'Zaragoza', 'España', 'ES3600810362910001228524', 'yolanda@batinsa.es', 'www.batinsa.es', '876167974', '', '', 1, NULL, '', 0, ''),
('B99423311', 'AESAR Estaciones de Servicio', 'Ctra. Castellón A-68 PK 233', 50013, 'Zaragoza', 'Zaragoza', 'España', 'ES4601820740380201558309', 'pilarsoto@nuevacartuja.com', '', '976490632', '', '', 0, NULL, '', 0, ''),
('B99433435', 'Pequeños Maestros', 'Maurice Ravel 37', 50012, 'Zaragoza', 'Zaragoza', 'España', 'ES5300496622712516107036', 'alejandra.reguero@gmail.com', '', '696238885', '876113550', '', 13, 2014, 'Espacio creado para cuidar y acompañar a los pequeños en. Su desarrollo y crecimiento mientras los padres trabajan. Educando en tribu, con Amor y cariño de la mano de las familias. Creando una extensión del Hogar y Familia.', 0, '@pequenosmaestros / @pequenosmaestros.rosales\r\n\r\nRosales: 876113550\r\nAzucarera: 976296554\r\nValdespartera: 650290465'),
('B99446056', 'NANA FOOD sl', 'CALLE NOGAL, 47. Polígono Malpica-Alfindén.', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', 'ES2720850152010330553270', 'bego@nanafood.es', 'http://www.nanafood.es', '', '', '', 6, NULL, '', 0, ''),
('B99453722', 'OPPIDUM TIC', 'Anda. De la Autonomía 7, edif CIEM, 1ª Planta Oficina Voda', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES8800815543520001184528', 'anafornies@oppidumtic.es', 'www.oppidumtic.es', '876281180', '', '', 2, NULL, '', 0, ''),
('B99455180', 'BOOGALOO', 'C/ Pedro María Ric 29', 50008, 'Zaragoza', 'Zaragoza', 'España', 'ES2100810170110001876791', 'info@boogaloovegetal.com', '', '876286127', '', '', 3, NULL, '', 0, ''),
('B99468803', 'METOPA', 'Avenida Cataluña 165 Local 6', 50014, 'Zaragoza', 'Zaragoza', 'España', 'ES4320855225510332682500', 'info@metopa.es', '', '976366758', '', '', 3, NULL, '', 0, ''),
('B99472151', 'Eneriz Y Gomez Asociados', 'Coso 34, 4ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES0901280400640100170757', 'ainaraenerizauria@gmail.com', 'http://www.audidatzaragoza.com/', '', '', '', 0, NULL, '', 0, ''),
('B99563264', 'Agreda Bus, S.L.', 'AVDA MANUEL RODRIGUEZ AYUSO 110', 50012, 'Zaragoza', 'Zaragoza', 'España', 'ES4120858425879400018498', 'cluque@alsa.es', '', '', '', '', 0, NULL, '', 0, ''),
('F22004311', 'FRIBIN', 'Partida Chubera', 22500, 'Binéfar', 'Huesca', 'España', 'PAGARÉ', 'cgallart@fribin.com', 'http://www.fribin.com/', '974431500', '', '', 0, NULL, '', 0, ''),
('F99374670', 'CERAMICAS EL CIERZO', 'Carretera Galluer Sanguesa KM 37,200', 50600, 'Ejea de los Caballeros', 'Zaragoza', 'España', 'ES6020855442310331611726', 'comercial@ceramicaselcierzo.com', 'www.ceramicaselcierzo.com', '', '', '', 0, NULL, '', 0, ''),
('G50107531', 'Asociación Aragonesa Pro Salud Mental (ASAPME)', 'C/ Ciudadela S/N', 50017, 'Zaragoza', 'Zaragoza', 'España', 'ES5220855228950331571613', 'alopez@asapme.org', 'www.asapme.org', '976532499', '', '', 54, NULL, '', 0, ''),
('G50689413', 'APASCIDE ARAGON', 'C/Manuel Lasala 16', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES0820850115510300253801', 'carmenasenfus@gmail.com', 'https://apascidearagon.es/', '', '', '', 0, NULL, '', 0, ''),
('G99338980', 'ARADE - ASOCIACION ARAGONESA PARA LA DEPENDENCIA', 'Via Hispanidad 152 Local', 50017, 'Zaragoza', 'Zaragoza', 'España', 'ES3230800065112412601417', 'gerencia@aradeasociacion.com', 'www.aradeasociacion.com', '976460354', '', '', 1, NULL, '', 0, ''),
('G99359788', 'ANA ISABEL VICENTE ARTERO', 'Calle Segundo Chomon', 50018, 'Zaragoza', 'Zaragoza', 'España', 'ES9201821294120205833490', 'anavicenteartero@gmail.com', '', '976525089', '', '', 0, NULL, '', 0, ''),
('G99380842', 'FUNDACION SESE', 'Virgen del Buen Recuerdo, 5', 50014, 'Zaragoza', 'Zaragoza', 'España', 'ES5620850133010101180223', 'ana.sese@gruposese.com', '', '', '', '', 0, NULL, '', 0, ''),
('G99466864', 'SENIORS EN RED', 'C/Coso 35, 2º Oficina 5', 50003, 'Zaragoza', 'Zaragoza', 'España', 'ES4621009723610200097201', 'seniors@seniorsenred.org', 'www.seniorsenred.org', '976112020', '', '', 0, NULL, '', 0, ''),
('J99407165', 'MS31', 'C/ Madre Sacramento 31', 50004, 'Zaragoza', 'Zaragoza', 'España', 'ES3900495789422595440390', 'rodrigopatricia@gmail.com', 'https://www.siapunto.es/', '976281409', '', '', 0, NULL, '', 0, ''),
('M666789471', 'HGO COMPLIANCE - PH ABOGADOS', 'Gran Vía 41, 1º D', 50006, 'Zaragoza', 'Zaragoza', 'España', 'ES3320860037660000280462', 'info@phabogados.com', 'www.phabogados.com', '876262128', '', '876874253', 3, NULL, '', 0, 'www.hgocompliance.weebly.com'),
('Q5018001G', 'UNIVERSA', 'Menendez Pelayo', 50009, 'Zaragoza', 'Zaragoza', 'España', 'PASAR RECIBO', 'ngarcia@unizar.es', '', '976761997', '', '', 0, NULL, '', 0, ''),
('X6741376F', 'Panel Sandwich Group SL', 'Coso 46', 500004, 'Zaragoza', 'Zaragoza', 'España', 'ES5300492813412894925555', 'katerina@panelsandwich.com', 'http://www.pnaelsandwich.com', '976900043', '', '', 55, NULL, '', 1, '');

DROP TABLE IF EXISTS `empresas_pertenecen_sectores`;
CREATE TABLE IF NOT EXISTS `empresas_pertenecen_sectores` (
  `eps_empresa` varchar(10) NOT NULL,
  `eps_sector` varchar(100) NOT NULL,
  PRIMARY KEY (`eps_empresa`,`eps_sector`),
  KEY `FKEmpresas_p718961` (`eps_sector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `empresas_pertenecen_sectores` (`eps_empresa`, `eps_sector`) VALUES
('25458719C', 'Cultural'),
('72997813E', 'Digital'),
('A82473018', 'Servicios'),
('A87456372', 'Salud y estética'),
('B09699505', 'Digital'),
('B09699505', 'Medioambiente'),
('B22146005', 'Salud y estética'),
('B50032002', 'Industria y tecnología'),
('B50032002', 'Medioambiente'),
('B50056183', 'Formación'),
('B50500263', 'Asesoría'),
('B50500263', 'Servicios'),
('B50633759', 'Servicios'),
('B50670264', 'Servicios'),
('B50670843', 'Decoración'),
('B50670843', 'Industria y tecnología'),
('B50799790', 'Asesoría'),
('B50909423', 'Asesoría'),
('B50921295', 'Asesoría'),
('B50921295', 'Servicios'),
('B72946148', 'Digital'),
('B72946148', 'Diseño y comunicación'),
('B72946148', 'Servicios'),
('B99050031', 'Asesoría'),
('B99050031', 'Servicios'),
('B99080772', 'Alimentación y consumo'),
('B99124588', 'Servicios'),
('B99254302', 'Servicios'),
('B99278160', 'Asesoría'),
('B99291528', 'Medioambiente'),
('B99291528', 'Servicios'),
('B99362972', 'Alimentación y consumo'),
('B99407173', 'Asesoría'),
('B99407173', 'Servicios'),
('M666789471', 'Asesoría'),
('M666789471', 'Servicios');

DROP TABLE IF EXISTS `premio`;
CREATE TABLE IF NOT EXISTS `premio` (
  `premio_year` int(4) NOT NULL,
  `premio_socia` int(5) NOT NULL,
  `premio_descripcion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '',
  PRIMARY KEY (`premio_year`,`premio_socia`),
  KEY `premio_socia` (`premio_socia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `premio` (`premio_year`, `premio_socia`, `premio_descripcion`) VALUES
(2003, 12, ''),
(2005, 23, ''),
(2006, 21, ''),
(2009, 7, ''),
(2009, 138, ''),
(2010, 2, ''),
(2010, 4, ''),
(2011, 26, ''),
(2012, 29, ''),
(2012, 34, ''),
(2013, 32, ''),
(2013, 33, ''),
(2014, 17, ''),
(2014, 35, ''),
(2014, 38, ''),
(2015, 22, ''),
(2015, 41, ''),
(2015, 57, ''),
(2016, 56, ''),
(2016, 58, ''),
(2016, 129, ''),
(2017, 64, ''),
(2018, 77, ''),
(2018, 78, ''),
(2018, 79, ''),
(2019, 83, ''),
(2019, 91, ''),
(2019, 92, ''),
(2019, 93, ''),
(2020, 29, ''),
(2020, 47, ''),
(2020, 111, ''),
(2020, 114, ''),
(2020, 115, ''),
(2021, 135, ''),
(2021, 138, ''),
(2022, 43, ''),
(2022, 74, ''),
(2022, 161, ''),
(2022, 163, '');

DROP TABLE IF EXISTS `recibo`;
CREATE TABLE IF NOT EXISTS `recibo` (
  `rec_year` int(4) NOT NULL,
  `rec_semestre` varchar(10) NOT NULL,
  `rec_cod` int(5) NOT NULL,
  `rec_cuantia` int(3) DEFAULT NULL,
  `rec_concepto` varchar(50) DEFAULT NULL,
  `rec_nombre` varchar(100) NOT NULL,
  `rec_nif` varchar(10) NOT NULL,
  `rec_direccion` varchar(200) DEFAULT NULL,
  `rec_cp` int(5) NOT NULL,
  `rec_poblacion` varchar(100) NOT NULL,
  `rec_provincia` varchar(50) NOT NULL,
  `rec_pais` varchar(50) NOT NULL,
  `rec_fecha` date DEFAULT NULL,
  `rec_socia` int(5) NOT NULL,
  `rec_iban` varchar(24) DEFAULT NULL,
  `rec_metodo_pago` varchar(50) DEFAULT NULL,
  `rec_fecha_baja` date DEFAULT NULL,
  PRIMARY KEY (`rec_year`,`rec_semestre`,`rec_cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `rol_nivel` tinyint(4) NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`rol_nivel`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `rol` (`rol_nivel`, `rol_nombre`) VALUES
(10, 'Root'),
(20, 'Administrador'),
(30, 'Usuario');

DROP TABLE IF EXISTS `sector`;
CREATE TABLE IF NOT EXISTS `sector` (
  `sect_nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`sect_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `sector` (`sect_nombre`) VALUES
('Alimentación y consumo'),
('Asesoría'),
('Construcción'),
('Cultural'),
('Decoración'),
('Digital'),
('Diseño y comunicación'),
('Formación'),
('Industria y tecnología'),
('Inmobiliaria'),
('Medioambiente'),
('Moda y joyas'),
('Salud y estética'),
('Servicios'),
('Social');

DROP TABLE IF EXISTS `socia`;
CREATE TABLE IF NOT EXISTS `socia` (
  `soc_cod` int(5) NOT NULL AUTO_INCREMENT,
  `soc_nif` varchar(10) DEFAULT NULL,
  `soc_alta` tinyint(1) NOT NULL,
  `soc_nombre` varchar(50) NOT NULL,
  `soc_apellidos` varchar(100) NOT NULL,
  `soc_email` varchar(80) NOT NULL,
  `soc_web` varchar(100) DEFAULT '',
  `soc_metodo_pago` varchar(50) NOT NULL DEFAULT '',
  `soc_dir` varchar(200) NOT NULL DEFAULT '',
  `soc_cp` int(5) NOT NULL,
  `soc_poblacion` varchar(100) NOT NULL DEFAULT '',
  `soc_provincia` varchar(50) NOT NULL DEFAULT '',
  `soc_pais` varchar(50) NOT NULL DEFAULT '',
  `soc_es_autonoma` tinyint(1) NOT NULL,
  `soc_tlf` varchar(14) DEFAULT '',
  `soc_movil` varchar(14) DEFAULT '',
  `soc_fax` varchar(14) DEFAULT '',
  `soc_iban` varchar(24) DEFAULT '',
  `soc_cuota` varchar(100) NOT NULL DEFAULT '',
  `soc_fact_nombre` varchar(100) NOT NULL DEFAULT '',
  `soc_fact_nif` varchar(10) NOT NULL DEFAULT '',
  `soc_fact_dir` varchar(200) NOT NULL DEFAULT '',
  `soc_fact_cp` int(5) NOT NULL,
  `soc_fact_poblacion` varchar(100) NOT NULL DEFAULT '',
  `soc_fact_provincia` varchar(50) NOT NULL DEFAULT '',
  `soc_fact_pais` varchar(50) NOT NULL DEFAULT '',
  `soc_referida_por` int(5) DEFAULT NULL,
  `soc_notas` varchar(1000) DEFAULT '',
  PRIMARY KEY (`soc_cod`),
  KEY `FKsocia171277` (`soc_cuota`),
  KEY `fk_srps` (`soc_referida_por`),
  KEY `soc_nif` (`soc_nif`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `socia` (`soc_cod`, `soc_nif`, `soc_alta`, `soc_nombre`, `soc_apellidos`, `soc_email`, `soc_web`, `soc_metodo_pago`, `soc_dir`, `soc_cp`, `soc_poblacion`, `soc_provincia`, `soc_pais`, `soc_es_autonoma`, `soc_tlf`, `soc_movil`, `soc_fax`, `soc_iban`, `soc_cuota`, `soc_fact_nombre`, `soc_fact_nif`, `soc_fact_dir`, `soc_fact_cp`, `soc_fact_poblacion`, `soc_fact_provincia`, `soc_fact_pais`, `soc_referida_por`, `soc_notas`) VALUES
(1, '25168295Q', 0, 'Ainhoa', 'Ruiz Pinilla', 'aruizpinilla@gmail.com', '', '', 'C/ Silveria Fañanas, 57, local', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, '976403940', '636486402', '976332362', 'ES5700810170190001642369', 'Normal', 'TRIFOLIUM', '25168295Q', 'C/ Silveria Fañanas, 57, local', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, 'aruiz@trifoliumca.com'),
(2, '', 1, 'Ana', 'Gallizo Bericat', 'angeles.lopez@economistas.org', '', 'DOMICILIACIÓN BANCARIA', 'Avda. Cosculluela, 17', 50600, 'Ejea de los Caballeros', 'Zaragoza', 'España', 0, '976281356', '654854879', '', 'ES7401820759170201543237', 'Reducida', 'GALLIZO DISEÑO DE BAÑOS', '', 'Avda. Cosculluela, 17', 50600, 'Ejea de los Caballeros', 'Zaragoza', 'España', 0, ''),
(3, '17220134B', 1, 'María del Mar', 'Martínez Marqués', 'marimarmartinez@arizaabogados.com', 'www.arizaabogados.com', 'DOMICILIACIÓN BANCARIA', 'C/ Conde de Aranda, nº 1, 3º dcha', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '976444200', '600505309', '', 'ES1100492833972615981995', 'Normal', 'ARIZA ABOGADOS', '17220134B', 'C/ Conde de Aranda, nº 1, 3º dcha', 0, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(4, '25458719C', 1, 'Myriam', 'Monterde Maldonado', 'info@arteporcuatro.com', 'www.arteporcuatro.com', 'DOMICILIACIÓN BANCARIA', 'C/ Sevilla, 21, escal dcha, 5ºC', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '', '670575234', '', 'ES1100650123170001045237', 'Normal', 'ARTE POR CUATRO', '25458719C', 'C/ Sevilla, 21, escal dcha, 5ºC', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(5, '', 1, 'Mª Jesús', 'Lorente Ozcáriz', 'mjlorente@bioknostic.com', 'http://www.bioknostic.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Mendez Nuñez, 10, 1ºB', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976489332', '676964269', '976535820', 'ES6100492833912116300827', 'Normal', 'BIOKNOSTIC, S.L.', 'B50670264', 'C/ Mendez Nuñez, 10, 1ºB', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(6, '', 1, 'María José', 'Galardón Arregui', 'mgalardon@arainnova.com ', 'www.arainnova.com', 'DOMICILIACIÓN BANCARIA', 'C/ Clara Campoamor  16 3ª', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976519722', '625401136', '', 'ES7330800065182260834524', 'Normal', 'ARAGON INNOVA GESTIONA Y MAS SL.', 'B99407173', 'C/ Clara Campoamor  16 3ª', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(7, '', 1, 'Susana', 'Ruberte', 'susana@bodegasruberte.com', 'http://www.gruporuberte.com/legal.asp', 'DOMICILIACIÓN BANCARIA', 'Avda. de la Paz, 28', 50520, 'Magallón', 'Zaragoza', 'España', 0, '976858063', '625603960', '976858475', 'ES3500810578090001116418', 'Normal', 'BODEGAS RUBERTE HNOS.', 'B99362972', 'Avda. de la Paz, 28', 50520, 'Magallón', 'Zaragoza', 'España', NULL, ''),
(8, '25147473D', 1, 'Carmen', 'Urbano Gómez', 'carmenurbanogomez@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Avda. Madrid, 11-13, 2º E', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976794371', '629016409', '976794361', 'ES2500495480202916240729', 'Normal', 'Directora General de Promoción Agroalimentaria del Gobierno de Aragón ', '25147473D', 'Avda. Madrid, 11-13, 2º E', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(9, '', 1, 'Mª Carmen', 'Jarque Nasarre', 'mjarque@izquierdofp.es', 'http://www.ceforizquierdo.es/', 'DOMICILIACIÓN BANCARIA', 'Avda. Tenor Fleta, 57, Pasaje', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976486271', '', '976482761', 'ES8321002370170200036825', 'Normal', 'CEFOR IZQUIERDO ', '', 'Avda. Tenor Fleta, 57, Pasaje', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(10, '25473145R', 0, 'Yolanda', 'Fraile Calvo', 'clicbaterias@gmail.com', '', '', 'C/ Brazal Valseca, 5, bajos', 50016, 'Zaragoza', 'ZaragozA', 'España', 0, '647450175', '607293324', '976573119', 'ES1021001783500200107485', 'Normal', 'CLIC BATERÍAS', '25473145R', 'C/ Brazal Valseca, 5, bajos', 50016, 'Zaragoza', 'ZaragozA', 'España', 0, 'info@clicbaterias.es'),
(11, '', 1, 'Mónica', 'Muñoz Fraile', 'natalia@innovazgz.com', 'www.innovazgz.com', 'DOMICILIACIÓN BANCARIA', 'C/ Alfonso nº 17, planta 3ª Oficina 2', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '976233353', '639739146', '', 'ES7921003847100200021978', 'Normal', 'INNOVA EVENTOS ZARAGOZA SL U', 'B99124588', 'C/ Alfonso nº 17, planta 3ª Oficina 2', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(12, '', 1, 'Carmina', 'Sanz Blasco', 'estudio@elrincondecoracion.com', 'http://www.elrincondecoracion.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Sanclemente, 26, 3º', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976482625', '', '976482625', 'ES8920850161350330053980', 'Normal', 'EL RINCÓN', '', 'C/ Sanclemente, 26, 3º', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(13, '', 1, 'Teresa', 'Garcés Vaquero', 'etc@equipoetc.com', 'http://equipoetc.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Alonso de Aragón, 1, 4ºB', 50010, 'Zaragoza', 'Zaragoza', 'España', 0, '', '670287549', '', 'ES8321000730370201150992', 'Normal', 'EQUIPO TÉCNICO Y DE CALIDAD S.L.', '', 'C/ Alonso de Aragón, 1, 4ºB', 50010, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(14, '17721834N', 1, 'Cristina', 'Equiza López', 'cristinaequiza@equiza.es', 'www.cristinaequiza.es', 'DOMICILIACIÓN BANCARIA', 'Avda. Cesareo Alierta, 16, escal 4, 9º dcha', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976227050', '646297501', '', 'ES5720855234850330938400', 'Normal', 'GABINETE PSICOLOGICO CRISTINA EQUIZA LÓPEZ', '17721834N', 'Avda. Cesareo Alierta, 16, escal 4, 9º dcha', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(15, '', 1, 'Carmen', 'Feced Esteban', 'gamma@gammasl.com', 'www.gammaservicios.com', 'DOMICILIACIÓN BANCARIA', 'C/ La Milagrosa 5-7, Local 9', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '976382115', '606401155', '', 'ES4130800065162291741920', 'Normal', 'GAMMA, SERVICIOS', 'B50633759', 'C/ La Milagrosa 5-7, Local 9', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(16, '', 1, 'Beatriz', 'López Sanz', 'blopez@ibermac.com', 'http://www.ibermac.com/', 'DOMICILIACIÓN BANCARIA', 'C/ San Jorge, 33, esc dcha, 1º Dcha', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976393593', '', '976204701', 'ES3600810170110001787087', 'Normal', 'IBERMAC ASESORES, S.L. ', 'B50799790', 'C/ San Jorge, 33, esc dcha, 1º Dcha', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(17, '', 1, 'María', 'López Palacín', 'marialopez@grupoilssa.com', 'www.grupoilssa.com', 'DOMICILIACIÓN BANCARIA', 'Ctra. Castellón 58, KM 2, 800', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976415200', '659449102', '', 'ES2500610384110000530114', 'Normal', 'GRUPO LOPEZ SORIANO', 'B50032002', 'Ctra. Castellón 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(18, '', 1, 'Lucía', 'Berlanga Quintero', 'direccion@grupointelecto.com', 'http://www.grupointelecto.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Daroca, 77, 1º izda', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, '976200412', '620596933', '976349186', 'ES6600491541402710023938', 'Normal', 'INTELECTO', '', 'C/ Daroca, 77, 1º izda', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(19, '', 1, 'Miriam Elena', 'Almazán Monge', 'me.almazan@afiris.es', 'http://www.afiris.es/', 'DOMICILIACIÓN BANCARIA', 'C/ Isaac Peral, 1, 1ºE', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976234430', '', '976234537', 'ES7400811986580001091415', 'Normal', 'AFIRIS ASESORES, S.L. (IRIS)', '', 'C/ Isaac Peral, 1, 1ºE', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(20, '', 1, 'Rosaura', 'Morata Ruiz', 'rosaura@manuellarraga.com', 'http://www.manuellarraga.com/', 'DOMICILIACIÓN BANCARIA', 'Pol. Malpica, C/E, parcela 9-10', 50016, 'Zaragoza', 'Zaragoza', 'España', 0, '976573715', '', '976572731', 'ES6920860025193300147639', 'Normal', 'MANUEL LARRAGA  S.L.', '', 'Pol. Malpica, C/E, parcela 9-10', 50016, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(21, '', 1, 'Mª José', 'Burgués Camarasa', 'comercial@mesbur.com', 'no web', 'DOMICILIACIÓN BANCARIA', 'Ronda Estación, 4, bajos', 22005, 'Huesca', 'Huesca', 'España', 0, '974214400', '', '974215530', 'ES9721001665680200043522', 'Reducida', 'MESBUR S.L. CENTRO MÉDICO DE ESTÉTICA', '', 'Ronda Estación, 4, bajos', 22005, 'Huesca', 'Huesca', 'España', 0, ''),
(22, '', 1, 'Ana', 'López Trenco', 'alopez@asapme.org', 'www.asapme.org', 'DOMICILIACIÓN BANCARIA', 'C/ Ciudadela S/N', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, '976532499', '608166527', '', 'ES5220855228950331571613', 'Normal', 'ASAPME', 'G50107531', 'C/ Ciudadela S/N', 50017, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(23, '', 1, 'Pilar', 'Muro Navarro', 'pilar.muronavarro@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ Méndez Coarasa', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '', '651847716', '', 'ES1600810170140001478549', 'Normal', 'PHILYRA SA', 'A87456372', 'C/ Méndez Coarasa', 50012, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(24, '', 0, 'Blanca', 'Fernández-Galiano Campos', 'blancagaliano@gmail.com', '', '', 'Paseo de la Constitución 18-20', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976957258', '696917219', '976451132', 'ES0520387413533000477113', 'Normal', 'VITALIA', '', 'Paseo de la Constitución 18-20', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(25, '', 1, 'Mª Pilar', 'Hernández Blasco', 'info@phabogados.com', 'www.phabogados.com', 'DOMICILIACIÓN BANCARIA', 'Gran Vía 41, 1º D', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '876262128', '666789471', '876874253', 'ES3320860037660000280462', 'Normal', 'HGO COMPLIANCE - PH ABOGADOS', 'M666789471', 'Gran Vía 41, 1º D', 50006, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(26, '', 1, 'Ruth', 'Lázaro Torres', 'ruth@taisi.es', '', 'DOMICILIACIÓN BANCARIA', 'Avda. Pascual Marquina s/n', 50300, 'Calatayud', 'Zaragoza', 'España', 0, '976882028', '605941416', '976882568', 'ES3730210001641064437120', 'Normal', 'TAISI', '', 'Avda. Pascual Marquina s/n', 50300, 'Calatayud', 'Zaragoza', 'España', 0, ''),
(27, '', 1, 'Isabel', 'Lahuerta Bellido', 'isabel@udeser.com', '', 'DOMICILIACIÓN BANCARIA', 'Mercazaragoza, local 2,', 50014, 'Zaragoza', 'Zaragoza', 'España', 0, '976471216', '670276411', '976472747', 'ES8800810362910001145720', 'Normal', 'UDESER ', 'B50500263', 'Mercazaragoza, local 2,', 50014, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(28, '', 1, 'Mª Pilar', 'Dancausa', 'asesoria@grupodancausa.com', 'www.yovoyasesores.com', 'DOMICILIACIÓN BANCARIA', 'C/ Lacarra de Miguel, nº 29, 2º', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976230837', '679533388', '976230837', 'ES4401280401090100038136', 'Normal', 'YOVOY ASESORES', 'B99050031', 'C/ Lacarra de Miguel, nº 29, 2º', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(29, '', 1, 'Nathalia', 'Antas Martínez', 'nathalia@zaragozaservicios.es', 'www.zaragozaservicios.es', 'DOMICILIACIÓN BANCARIA', 'Calle Río Duero 13 Local   ', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '876776400', '637229745', '', 'ES9800815583480001115420', 'Normal', 'ZARAGOZA SERVICIOS', 'B99254302', 'Calle Río Duero 13 Local   ', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(30, '', 1, 'Mª Jesús', 'Aranda Casaus', 'maranda@luzgma.com', 'www.luzgma.com', 'DOMICILIACIÓN BANCARIA', 'Pº Independencia, 24-26, 5º 14', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976226410', '679436366', '', 'ES4920860000203301387017', 'Normal', 'LUZ DE GESTIÓN Y MEDIOAMBENTE', '', 'Pº Independencia, 24-26, 5º 14', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(31, '73258072J', 1, 'Ana Cristina', 'Esteban Baranda', 'anacrisesteban@hotmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Ronda Sur, nº 1, Puigmoreno-Alcañiz', 44660, 'Alcañiz', 'teruel', 'España', 0, '978838295', '616533328', '', 'ES4301820751590201546308', 'Normal', 'ALCANALYTICS', '', 'Ronda Sur, nº 1, Puigmoreno-Alcañiz', 44660, 'Alcañiz', 'teruel', 'España', 31, ''),
(32, '', 0, 'Ester', 'Ariza Espierrez', 'ester_ariza@airfal.com', '', '', 'c/ Río Ésera nº 4', 50830, 'Villanueva de Gállego', 'Zaragoza', 'España', 0, '976185909', '670766582', '976185809', 'ES5300810292760001043213', 'Normal', 'AIRFALINTERNACIONAL S.L.', '', 'c/ Río Ésera nº 4', 50830, 'Villanueva de Gállego', 'Zaragoza', 'España', NULL, ''),
(33, '', 1, 'Irene Carmen', 'Lequerica', 'dicsa@dicsaes.com', 'www.dicsaes.com', 'DOMICILIACIÓN BANCARIA', 'Pol. Industrial Alcalde Caballero C/ del Buen Acuerdo s/n', 50014, 'Zaragoza', 'Zaragoza', 'España', 0, '976464100', '629347490', '', 'ES1701822407950000043846', 'Normal', 'DICSA', 'A50081546', 'Pol. Industrial Alcalde Caballero C/ del Buen Acuerdo s/n', 50014, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(34, '', 1, 'Berta', 'Lorente Torrano', 'bertalorente@zaforsa.es', 'www.zaforsa.com', 'DOMICILIACIÓN BANCARIA', 'Pol. Ind. El Portazgo, nave 51/52', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, '976322211', '607758168', '', 'ES4200491824442310022376', 'Normal', 'ZAFORSA Zaragozana de Formularios SA', 'A50054313', 'Pol. Ind. El Portazgo, nave 51/52', 50011, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(35, '', 0, 'Agnes', 'Daroca Herrero', 'info@doscuartos.com', '', '', 'C/ Dionisio Alastrue, 14', 50174, 'Villafranca de Ebro', 'Zaragoza', 'España', 0, '976167180', '', '', 'ES8530350292212920022663', 'Normal', 'DOS CUARTOS COMUNICACIÓN', '', 'C/ Dionisio Alastrue, 14', 50174, 'Villafranca de Ebro', 'Zaragoza', 'España', NULL, ''),
(36, '', 1, 'Carmen', 'Iribas', 'iribas.carmen@gmail.com', '', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '', '627111458', '', 'ES8720850152080330091873', 'Normal', 'INNG DIRECT', '', '', 0, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(37, '', 0, 'Rocío', 'de San Pío', 'rocio.desanpio@gmail.com', '', '', 'Clara Campoamor 21, 6º A', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '', '615556830', '', 'ES4220850113630330158092', 'Normal', 'CONSULTORIA EN ORGANIZACIÓN Y RRHH', '', 'Clara Campoamor 21, 6º A', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(38, '', 0, 'Marisa', 'Marín', 'direccion@multitecsa.es', '', '', 'C/ Ramiro I de Aragón, 22 local ', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, '976403650', '', '', 'ES6621002950950200104510', 'Normal', 'MULTITEC SL', '', 'C/ Ramiro I de Aragón, 22 local ', 50017, 'Zaragoza', 'Zaragoza', 'España', NULL, 'Baja por falta de pago de cuotas y no poder contactar con ella'),
(39, '', 1, 'Olga', 'Pinilla Barcelona', 'olga@arteymerchan.com', 'www.arteymerchan.com', 'DOMICILIACIÓN BANCARIA', 'C/ Bernardo Fita 21', 50005, 'Zaragoza', 'Zaragoza', 'España', 0, '976974479', '640236764', '', 'ES5530350310913100009454', 'Normal', 'ARTEYMERCHAN', 'B99393704', 'C/ Bernardo Fita 21', 50005, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(40, '', 1, 'Carmen', 'Asensio Fuster', 'carmenasenfus@gmail.com', 'https://apascidearagon.es/', 'DOMICILIACIÓN BANCARIA', 'C/Manuel Lasala 16', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '', '655409231', '', 'ES0820850115510300253801', 'Normal', 'APASCIDE ARAGON', 'G50689413', 'C/Manuel Lasala 16', 50006, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(41, '29104356H', 1, 'Vicky', 'Calavia Sos', 'vickycalavia@gmail.com', 'www.vickycalavia.com', 'DOMICILIACIÓN BANCARIA', 'Calle Elche 8, 1ªA', 50002, 'Zaragoza', 'Zaragoza', 'España', 0, '', '667255350', '', 'ES3820855208410332584796', 'Normal', 'CALA DOC PRODUCCIONES', '29104356H', 'Calle Elche 8, 1ªA', 50002, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(42, '18019709Z', 1, 'Begoña', 'Julián Arruego', 'bjulian@abogadosjulian.com', 'www.abogadosjulian.com', 'DOMICILIACIÓN BANCARIA', 'San Clemente 25 4ª Puerta', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976483507', '609694923', '', 'ES1300817220130001146619', 'Normal', 'JULIAN ABOGADOS', '18019709Z', 'San Clemente 25 4ª Puerta', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(43, '', 1, 'Sonia', 'Beltrán Domínguez', 'sbeltran@kalfrisa.com', 'http://www.kalfrisa.com/', 'DOMICILIACIÓN BANCARIA', 'PTR  Lopez Soriano, Crta Valmadrid', 50720, 'Zaragoza', 'Zaragoza', 'España', 0, '976420731', '666374781', '976471595', 'ES1821001645150100119529', 'Normal', 'KALFRISA SA', 'A50013465', 'PTR  Lopez Soriano, Crta Valmadrid', 50720, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(44, '', 1, 'Ana Cristina', 'Larre Lancis', 'ana.larre@bodegaslarre.com', '', 'DOMICILIACIÓN BANCARIA', 'Allue Salvador 32 2ª Izq', 50830, 'Villanueva de Gállego', 'Zaragoza', 'España', 0, '876115019', '607982074', '', 'ES8320851275470330046591', 'Normal', 'BODEGAS LARRE', '', 'Allue Salvador 32 2ª Izq', 50830, 'Villanueva de Gállego', 'Zaragoza', 'España', NULL, ''),
(45, '', 1, 'Paquita', 'Morata Prieto', 'gerencia@aradeasociacion.com', 'www.aradeasociacion.com', 'DOMICILIACIÓN BANCARIA', 'Via Hispanidad 152 Local', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, '976460354', '618381997', '', 'ES3230800065112412601417', 'Normal', 'ARADE - ASOCIACION ARAGONESA PARA LA DEPENDENCIA', 'G99338980', 'Via Hispanidad 152 Local', 50017, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(46, '', 1, 'Nuria', 'Nuria Retornano', 'info@workingformacion.com', 'www.workingformacion.com', 'DOMICILIACIÓN BANCARIA', 'Paseo Rosales, 32, Local 9', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976242109', '649458113', '', 'ES8630350333703330010325', 'Normal', 'WORKING FORMACION', 'B99391849', 'Paseo Rosales, 32, Local 9', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(47, '', 1, 'Gema', 'Agustín Martínez', 'losmaizales@losmaizales.com', 'www.losmaizales.com', 'DOMICILIACIÓN BANCARIA', 'Poligono Argualas, Nave 52-B', 50012, 'Pinseque', 'Zaragoza', 'España', 0, '976656870', '615063706', '', 'ES1200810363320001230827', 'Normal', 'LOS MAIZALES RESIDENCIAS GERIATRICAS', 'B50501097', 'Poligono Argualas, Nave 52-B', 50012, 'Pinseque', 'Zaragoza', 'España', NULL, ''),
(48, '', 1, 'Pilar', 'Simón Montoya', 'pilar@simonasesores.es', 'www.simonasesores.es', 'DOMICILIACIÓN BANCARIA', 'C/ Alberto Duce 14 Bajo', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976742226', '618609676', '', 'ES8900811522950001161323', 'Normal', 'SIMON ASESORES', 'B50766930', 'C/ Alberto Duce 14 Bajo', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(49, '73076956E', 0, 'Mª José', 'Guillermo Guerra', 'mjoseg50@gmail.com', '', '', 'C/Río Aragón 35 M', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', 0, '976108096', '619858540', '', 'ES3820800665593041006560', 'Normal', 'SERVILOG', '73076956E', 'C/Río Aragón 35 M', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', NULL, ''),
(50, '', 1, 'Marta', 'Serrano González', 'marta@esisoluciones.es', 'www.esisoluciones.es', 'DOMICILIACIÓN BANCARIA', 'Camino Mosquetera 45 Local', 50010, 'Zaragoza', 'Zaragoza', 'España', 0, '976300140', '609164703', '', 'ES2700815583400001013106', 'Normal', 'ESI SOLUCIONES TIC', 'B50919893', 'Camino Mosquetera 45 Local', 50010, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(51, '', 1, 'Mª Luz', 'Martínez Fernández', 'mluz.martinez@economistas.org', 'www.ejecutivosenelcambio.es', 'DOMICILIACIÓN BANCARIA', 'Avda. Plabo Ruiz Picasso 16 - 2B', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '', '679141204', '', 'ES3001826925110201567375', 'Normal', 'YACANA ESTUDIOS Y SERVICIOS SL', 'B99146458', 'Avda. Plabo Ruiz Picasso 16 - 2B', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(52, 'B99453722', 0, 'Ana María', 'Forniés Orduna', 'anafornies@oppidumtic.es', '', '', 'Anda. De la Autonomía 7, edif CIEM, 1ª Planta Oficina Voda', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '876281180', '630923925', '', 'ES8800815543520001184528', 'Normal', 'OPPIDUM TIC', 'B99453722', 'Anda. De la Autonomía 7, edif CIEM, 1ª Planta Oficina Voda', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(53, '', 1, 'Pilar', 'Aguerri Martínez', 'info@boogaloovegetal.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ Pedro María Ric 29', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '876286127', '610217006', '', 'ES2100810170110001876791', 'Normal', 'BOOGALOO', 'B99455180', 'C/ Pedro María Ric 29', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(54, '25135411E', 0, 'Mª Ángeles', 'López Artal', 'angeles.lopez@economistas.org', '', '', 'C/ Don Jaime I, 16', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976281356', '654854879', '', 'ES7720855204670333239322', 'Normal', 'TÍTULO PARTICULAR / COLEGIO OFICIAL DE ECONOMISTAS DE ARAGÓN', '25135411E', 'C/ Don Jaime I, 16', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(55, '', 1, 'Yolanda', 'Lainez Larrosa', 'yolanda@batinsa.es', 'www.batinsa.es', 'DOMICILIACIÓN BANCARIA', 'C/ Val. De Carbonera', 50162, 'Villamayor de Gállego', 'Zaragoza', 'España', 0, '876167974', '658795782', '', 'ES3600810362910001228524', 'Normal', 'BATINSA', 'B99413908', 'C/ Val. De Carbonera', 50162, 'Villamayor de Gállego', 'Zaragoza', 'España', NULL, ''),
(56, '', 1, 'Yeresi', 'Arnal Pérez', 'yarnal@implaser.com', 'www.implaser.com', 'DOMICILIACIÓN BANCARIA', 'Polígono Borao Norte Nave 5A - B - C', 50172, 'Alfajarín', 'Zaragoza', 'España', 0, '976455088', '', '', 'ES3520855470620330232051', 'Normal', 'IMPLASER', 'B50776947', 'Polígono Borao Norte Nave 5A - B - C', 50172, 'Alfajarín', 'Zaragoza', 'España', NULL, ''),
(57, '18418296B', 1, 'Raquel', 'Esteban', 'raquel@bodasdeisabel.com', 'www.bodasdeisabel.com', 'DOMICILIACIÓN BANCARIA', 'Plaza Catedral 9 Bajo', 44001, 'Teruel', 'Teruel', 'España', 0, '978618504', '630045932', '', 'ES8230800001862063662718', 'Reducida', 'FUNDACION BODAS DE ISABEL', '18418296B', 'Plaza Catedral 9 Bajo', 44001, 'Teruel', 'Teruel', 'España', 0, ''),
(58, '', 1, 'Mª Eugenia', 'Díaz', 'm.eugenia@industrias-ediaz.com', 'www.industrias-ediaz.com', 'EFECTIVO', 'Ctra. Castellon KM 6,2', 50720, 'La Cartuja Baja', 'Zaragoza', 'España', 0, '976454007', '676478647', '', 'EFECTIVO', 'Normal', 'INDUSTRIAS E DIAZ', 'A50130277', 'Ctra. Castellon KM 6,2', 50720, 'La Cartuja Baja', 'Zaragoza', 'España', NULL, ''),
(59, '', 1, 'María', 'Puig Forcano', 'mpuig@sanleonenergy.com', 'www.sanleonenergy.com', 'DOMICILIACIÓN BANCARIA', 'Paseo Independencia 24-26, 6º 2', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976974720', '619039611', '', 'ES0420855200820333403934', 'Normal', 'SAN LEON ENERGY PLC', 'B65295560', 'Paseo Independencia 24-26, 6º 2', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(60, 'A50089812', 0, 'Carmen', 'Fuertes Lanzuela', 'carmen@drolimsa.es', '', '', 'Avenida Zaragoza 50', 50412, 'Cadrete', 'Zaragoza', 'España', 0, '976503197', '620822675', '', 'ES0520855445770330365026', 'Normal', 'DROLIMSA - DROGERÍA Y LIMPIEZA S.A.', 'A50089812', 'Avenida Zaragoza 50', 50412, 'Cadrete', 'Zaragoza', 'España', NULL, ''),
(61, '', 1, 'María José', 'Gil González', 'mgilgon@mapfre.com', '', 'DOMICILIACIÓN BANCARIA', 'Fueros de Aragón 24', 50500, 'Tarazona', 'Zaragoza', 'España', 0, '', '646540118', '', 'ES7420800665563041001306', 'Normal', 'PUENTECRISTO SEGUROS SL', 'B50792688', 'Fueros de Aragón 24', 50500, 'Tarazona', 'Zaragoza', 'España', NULL, ''),
(62, '', 1, 'Olga', 'Pueyo Vera', 'info@cnlossitios.com', 'www.cnlossitios.com', 'DOMICILIACIÓN BANCARIA', 'C/ Sanclemente 25 - 4º Derecha (Esquina Plaza de  Los Sitios)', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976232100', '616998497', '', 'ES3120385835106000558908', 'Normal', 'CENTRO DE NEGOCIOS LOS SITIOS', 'B50566652', 'C/ Sanclemente 25 - 4º Derecha (Esquina Plaza de  Los Sitios)', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(63, '18935660J', 1, 'Teresa María', 'Gonzalo Peris', 'teresamaria@teresamaria.es', 'www.maravril.es', 'DOMICILIACIÓN BANCARIA', 'Paseo de la Mina 1, Entresuelo', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '876704381', '673685303', '', 'ES7601280400640100165962', 'Normal', 'MARA VRIL', '18935660J', 'Paseo de la Mina 1, Entresuelo', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(64, '', 1, 'Clara', 'Arpa Azofra', 'clara@arpaemc.com', 'www.arpaemc.com', 'OTROS', 'Polígono Industrial Centrovía, Calle La Habana 25', 50198, 'La Muela', 'Zaragoza', 'España', 0, '976144770', '678645408', '', 'CONFIRMING', 'Normal', 'ARPA EQUIPOS MOVILES DE CAMPAÑA', 'A50051218', 'Polígono Industrial Centrovía, Calle La Habana 25', 50198, 'La Muela', 'Zaragoza', 'España', NULL, ''),
(65, '18209654W', 1, 'Isabel', 'Eguillor Garayoa', 'ieguil@mapfre.com', '', 'DOMICILIACIÓN BANCARIA', 'Plaza Emperador Carlos V', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '', '690933894', '', 'ES2120384500233000446532', 'Normal', 'MAPFRE ESPAÑA', '18209654W', 'Plaza Emperador Carlos V', 50009, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(66, '', 1, 'Pilar', 'Soto Avellanas', 'pilarsoto@nuevacartuja.com', '', 'DOMICILIACIÓN BANCARIA', 'Ctra. Castellón A-68 PK 233', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976490632', '670748987', '', 'ES4601820740380201558309', 'Normal', 'AESAR Estaciones de Servicio', 'B99423311', 'Ctra. Castellón A-68 PK 233', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(67, '', 1, 'Cristina', 'Marín Chaves', 'info@metopa.es', '', 'DOMICILIACIÓN BANCARIA', 'Avenida Cataluña 165 Local 6', 50014, 'Zaragoza', 'Zaragoza', 'España', 0, '976366758', '619562912', '', 'ES4320855225510332682500', 'Normal', 'METOPA', 'B99468803', 'Avenida Cataluña 165 Local 6', 50014, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(68, '', 1, 'Ana María', 'Vicente García', 'info@vicentevision.com', '', 'DOMICILIACIÓN BANCARIA', 'Calle Visconti 13', 50500, 'Tarazona', 'Zaragoza', 'España', 0, '976640931', '652893106', '', 'ES8020851119510330338168', 'Normal', 'CENTRO OPTICO Y AUDITIVO VICENT', 'B99137721', 'Calle Visconti 13', 50500, 'Tarazona', 'Zaragoza', 'España', NULL, ''),
(69, '25145357D', 1, 'Silvia', 'García Molina', 'estudio.ruisenores@hotmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Calle Juan Pablo Bonet, 25 ', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '976021187', '638039899', '', 'ES6620850172540330258262', 'Normal', 'ESTUDIO RUISEÑORES', '25145357D', 'Calle Juan Pablo Bonet, 25 ', 50006, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(70, '', 1, 'Nieves', 'García Casarejos', 'ngarcia@unizar.es', '', 'PASAR RECIBO', 'Menendez Pelayo', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '976761997', '657201517', '', 'PASARRECIBO', 'Normal', 'UNIVERSA', 'Q5018001G', 'Menendez Pelayo', 50009, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(71, '', 1, 'Cristina', 'Llombart', 'milejardinhogar@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Calle Baja 30', 44600, 'Alcañiz', 'Teruel', 'España', 0, '978830519', '606375791', '', 'ES7830800008142058966124', 'Reducida', 'MILE ALCAÑIZ S.L.', 'B44201697', 'Calle Baja 30', 44600, 'Alcañiz', 'Teruel', 'España', 0, ''),
(72, '', 1, 'Ana Isabel', 'Vicente Artero', 'anavicenteartero@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Calle Segundo Chomon', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976525089', '650585425', '', 'ES9201821294120205833490', 'Normal', 'ANA ISABEL VICENTE ARTERO', 'G99359788', 'Calle Segundo Chomon', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(73, '29123690D', 1, 'Pilar', 'Aragüés Fumanal', 'soluciones@pilararagues.es', '', 'DOMICILIACIÓN BANCARIA', 'Avenida Ciudad de Soria 8 (PL3 La Terminal)', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '', '654632615', '', 'ES3420855255730330658992', 'Normal', 'PILAR ARAGÜÉS FUMANAL', '29123690D', 'Avenida Ciudad de Soria 8 (PL3 La Terminal)', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(74, '25467068L ', 1, 'Susana', 'Portero Enguita', 'susana@susanaportero.com', '', 'DOMICILIACIÓN BANCARIA', 'C/Santiago Castillo nº2', 50620, 'Casetas', 'Zaragoza', 'España', 0, '976775757', '661441511', '', 'ES8931910230184366682120', 'Normal', 'SUSANA PORTERO ENGUITA', '25467068L ', 'C/Santiago Castillo nº2', 50620, 'Casetas', 'Zaragoza', 'España', NULL, ''),
(75, '', 1, 'María José', 'Moliner Ostariz', 'mariajose.moliner@emeglobalbusiness.com', '', 'DOMICILIACIÓN BANCARIA', 'Avenida San Jose 103', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '867709685', '649238832', '', 'ES3801820740300201563404', 'Normal', 'KUCHEN KONZEPT / EME GLOBAL BUSINESS SL', 'B86511722', 'Avenida San Jose 103', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(76, '', 1, 'Berta', 'Recaj Tomás', 'bertarecaj@almenaracomunicacion.com ', '', 'DOMICILIACIÓN BANCARIA', 'Camino Torrevillarroya 43', 50190, 'Zaragoza', 'Zaragoza', 'España', 0, '', '651756487', '', 'ES6330350310913100015033', 'Normal', 'ALMENARA 1915', 'B99320327', 'Camino Torrevillarroya 43', 50190, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(77, '', 1, 'Susana María', 'Cuéllar Antoranz', 'anjul@anjul.es', '', 'DOMICILIACIÓN BANCARIA', 'Poligono Molino del Pilar, Calle Rudolf Diesel Nave 27', 50015, 'Zaragoza', 'Zaragoza', 'España', 0, '976526345', '653990666', '', 'ES0620850133040300152905', 'Normal', 'ANJUL INSTALACIONES S.L.', 'B50152149', 'Poligono Molino del Pilar, Calle Rudolf Diesel Nave 27', 50015, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(78, '', 1, 'Rosa', 'Plantagenet-Whyte Pérez', 'seniors@seniorsenred.org', 'www.seniorsenred.org', 'DOMICILIACIÓN BANCARIA', 'C/Coso 35, 2º Oficina 5', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '976112020', '610493576', '', 'ES4621009723610200097201', 'Normal', 'SENIORS EN RED', 'G99466864', 'C/Coso 35, 2º Oficina 5', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(79, '', 1, 'María Pilar', 'Sagaste Pueyo', 'comercial@ceramicaselcierzo.com', 'www.ceramicaselcierzo.com', 'DOMICILIACIÓN BANCARIA', 'Carretera Galluer Sanguesa KM 37,200', 50600, 'Ejea de los Caballeros', 'Zaragoza', 'España', 0, '', '665451246', '', 'ES6020855442310331611726', 'Normal', 'CERAMICAS EL CIERZO', 'F99374670', 'Carretera Galluer Sanguesa KM 37,200', 50600, 'Ejea de los Caballeros', 'Zaragoza', 'España', NULL, ''),
(80, 'B22210843', 0, 'Gemma', 'Bes Pérez', 'gemmabesperez@gmail.com', '', '', 'Edificio Aida / Calle Madre Rafols 2, Planta 8 Oficina 4', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '', '681342328', '', 'ES5400490391132990252020', 'Normal', 'NEWLINK EDUCATION', 'B22210843', 'Edificio Aida / Calle Madre Rafols 2, Planta 8 Oficina 4', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, '25137749 Z'),
(81, '', 1, 'María Belén', 'Arcos Uribe', 'info@abadiasietamo.es', 'www.abadiasietamo.es', 'DOMICILIACIÓN BANCARIA', 'C/Alta 10', 22120, 'Sietamo', 'Huesca', 'España', 0, '', '626033466/6297', '', 'ES2921002160720200193430', 'Reducida', 'ABADIA DE SIETAMO SL', 'B22337646', 'C/Alta 10', 22120, 'Sietamo', 'Huesca', 'España', 0, ''),
(82, '', 1, 'María', 'Alegre Puyod', 'mariapilar.alegre@fundacionsese.org', '', 'DOMICILIACIÓN BANCARIA', 'Virgen del Buen Recuerdo, 5', 50014, 'Zaragoza', 'Zaragoza', 'España', 0, '', '647979426', '', 'ES5620850133010101180223', 'Normal', 'FUNDACION SESE', 'G99380842', 'Virgen del Buen Recuerdo, 5', 50014, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(83, '', 1, 'Ana', 'Sesé Asensio', 'ana.sese@gruposese.com', '', 'DOMICILIACIÓN BANCARIA', 'Virgen del Buen Recuerdo, 5', 50014, 'Zaragoza', 'Zaragoza', 'España', 0, '', '607250401', '', 'ES8200810292730001343337', 'Normal', 'FUNDACION SESE', 'G99380842', 'Virgen del Buen Recuerdo, 5', 50014, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(84, '', 0, 'Ana', 'Rodríguez Betrián', 'rariza.director@gmail.com', '', '', 'Paseo del Molino', 50220, 'Zaragoza', 'Zaragoza', 'España', 0, '976879009', '645622769', '', 'ES6620850456100330095965', 'Normal', 'FUNDACIÓN ARIZA', '', 'Paseo del Molino', 50220, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(85, '25152771V', 1, 'Cristina', 'Garcés Pina', 'cristina@paravivir.es', 'http://www.paravivir.es', 'DOMICILIACIÓN BANCARIA', 'C/ Méndez Núñez 23, local', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '876043951', '626884551', '', 'ES8400811986540001304540', 'Normal', 'PARAVIVIR INMOBILARIA', '25152771V', 'C/ Méndez Núñez 23, local', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(86, '', 1, 'Margarita', 'Fernández Molinilla', 'mcoferadmon@gmail.com', 'www.cofercomunidades.es', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '976234921', '699103513', '', 'ES0900810578070001238525', 'Normal', 'COFER COMUNIDADES', '', '', 0, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(87, '', 1, 'Patricia', 'Rodrigo Puyó', 'rodrigopatricia@gmail.com', 'https://www.siapunto.es/', 'DOMICILIACIÓN BANCARIA', 'C/ Madre Sacramento 31', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976281409', '626325892', '', 'ES3900495789422595440390', 'Normal', 'MS31', 'J99407165', 'C/ Madre Sacramento 31', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, 'http://enmarcaciones.ms31.es/\r\nhttp://coworking.ms31.es/\r\nhttp://www.antoniapuyo.com/'),
(88, '29097971G', 1, 'Silvia', 'Lacruz Cebollero', 'slacruz@escuelacomunicando.es', '', 'DOMICILIACIÓN BANCARIA', 'C/ Cervantes, 32', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '976213987', '605564808', '', 'ES6901820745620201606133', 'Normal', 'ESCUELA COMUNICANDO', '29097971G', 'C/ Cervantes, 32', 50006, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(89, '73078869A', 1, 'Pilar', 'Garcés Lario', 'pilargarcespipa@gmail.com', 'http://www.pilargarcescomplementos.com/', 'DOMICILIACIÓN BANCARIA', 'Ildelfonso Manuel Gil 25', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '976510778', '617425301', '', 'ES4501826925150201518991', 'Normal', 'Perfumeria Complementos Pilar Garcés ', '73078869A', 'Ildelfonso Manuel Gil 25', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(90, '25460196R', 1, 'Mónica', 'Archelergues Ruiz', 'redaccion@conpequesenzgz.com', 'https://imagina-t.net/', 'DOMICILIACIÓN BANCARIA', 'Avenida Cesareo Alierta 9, 8ª 1ª', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976299202', '629312122', '', 'ES2620855215870331323348', 'Normal', 'IMAGINA-T', '25460196R', 'Avenida Cesareo Alierta 9, 8ª 1ª', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(91, '', 1, 'María Teresa', 'Crivillé Herrero', 'crial@bodegascrial.com', '', 'DOMICILIACIÓN BANCARIA', 'C/Arrabal de la Fuente 23', 44624, 'LLEDÓ', 'Teruel', 'España', 0, '978891909', '659496470', '', 'ES8230800015331000777027', 'Reducida', 'BODEGAS CRIAL', '', 'C/Arrabal de la Fuente 23', 44624, 'LLEDÓ', 'Teruel', 'España', 0, ''),
(92, '', 1, 'Esther', 'Borao Moros', 'esther@innovart.cc', '', 'DOMICILIACIÓN BANCARIA', 'C/Ángela López', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '', '639218444', '', '', 'Normal', 'ACADEMIA DE INVENTORES / THE IFS', '', 'C/Ángela López', 50009, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(93, '', 1, 'Camino', 'Ibarz Gil', 'camino.ibarz@tervalis.com', '', 'DOMICILIACIÓN BANCARIA', 'Avd/Sagunto 31, 4º A', 44002, 'Teruel', 'teruel', 'España', 0, '', '670056821', '', 'ES8930800041732357364716', 'Reducida', 'TERVALIS', '', 'Avd/Sagunto 31, 4º A', 44002, 'Teruel', 'teruel', 'España', 0, ''),
(94, '', 1, 'Asunción', 'Coyo', 'asunoa@hotmail.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ Única', 22474, 'Noales', 'Huesca', 'España', 0, '974554062', '628980368', '', 'ES9731910364545084247211', 'Reducida', 'PANADERIA FARRE DE L\'AIGUA', '', 'C/ Única', 22474, 'Noales', 'Huesca', 'España', 0, ''),
(95, '', 1, 'Alba Pilar', 'Boudet', 'alba@academiabestway.com', 'http://www.academiabestway.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Carlos Oriz Garcia 7', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, '', '610972595', '', 'ES5920385836416000692150', 'Normal', 'ACADEMIA BEST WAY', 'B99386584', 'C/ Carlos Oriz Garcia 7', 50011, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(96, '', 1, 'Sara', 'Comenge Zarroca', 'scomenge@atadeshuesca.org', 'http://www.atadeshuesca.org/', 'DOMICILIACIÓN BANCARIA', 'Travesia Ballesteros 10 Bajos', 22005, 'Huesca', 'Huesca', 'España', 0, '974212481', '647519941', '', 'ES1520852073120330357472', 'Reducida', 'VALENTIA', '', 'Travesia Ballesteros 10 Bajos', 22005, 'Huesca', 'Huesca', 'España', 0, ''),
(97, '73260844w', 1, 'Olivia', 'Peris Badía', 'olipeba@gmail.com', 'http://www.3ideascontadas.com/', 'DOMICILIACIÓN BANCARIA', 'C/ San Miguel 23, Dcha. 23', 44595, 'Valjunquera', 'Teruel', 'España', 0, '', '619345691', '', 'ES4330800008182479837821', 'Reducida', '3 IDEAS CONTADAS', '73260844w', 'C/ San Miguel 23, Dcha. 23', 44595, 'Valjunquera', 'Teruel', 'España', 0, ''),
(98, '254663468F', 1, 'Ruth', 'Barranco Raimundo', 'ruthbarrancointeriorismo@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ Camino de las Torres 8, esc. 1, despacho 10 A', 50002, 'Zaragoza', 'Zaragoza', 'España', 0, '', '651304956', '', 'ES5720855230120331415440', 'Normal', 'RUTH BARRANCO INTERIORISMO', '254663468F', 'C/ Camino de las Torres 8, esc. 1, despacho 10 A', 50002, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(99, '17729629X', 0, 'Marian', 'Gil', 'mariangil@mariangil.com', '', '', 'C/ Jerónimo Zurita 15 1º D', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '', '620536791', '', 'ES84/0128040069010011765', 'Normal', 'CRECIMIENTO COMO ACTITUD', '17729629X', 'C/ Jerónimo Zurita 15 1º D', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(100, '', 1, 'Patricia', 'Raimundo', 'patricia.raimundo@novaluz.es', 'www.novaluz.es', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '', '697789638', '', 'ES4201280790220100066973', 'Normal', 'NOVALUZ ENERGIA', 'B93661726', '', 0, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(101, '16802548J', 0, 'María', 'del Castillo', 'delcastillo.maria@gmail.com', '', '', 'Paseo Independencia, 8', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '', '637750865', '', 'ES5621032790390010002135', 'Normal', 'Integral Growth Consulting', '16802548J', 'Paseo Independencia, 8', 50009, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(102, '73089738Q', 1, 'Isabel', 'Conesa Magallón', 'isabel.conesa87@gmail.com', 'https://www.fabricandocontenidos.com/', 'DOMICILIACIÓN BANCARIA', 'Plaza San Miguel 1º 2ª', 44570, 'Calanda', 'Teruel', 'España', 0, '', '677255778', '', 'ES9130800011582384090318', 'Reducida', 'Fabricando Contenidos', '73089738Q', 'Plaza San Miguel 1º 2ª', 44570, 'Calanda', 'Teruel', 'España', 0, ''),
(103, '', 1, 'Ainara', 'Eneriz Auria', 'ainaraenerizauria@gmail.com', 'http://www.audidatzaragoza.com/', 'DOMICILIACIÓN BANCARIA', 'Coso 34, 4ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '', '685620149', '', 'ES0901280400640100170757', 'Normal', 'Eneriz Y Gomez Asociados', 'B99472151', 'Coso 34, 4ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(104, '', 1, 'María Antonia', 'Vila Roca', 'mav@sedovin.com', 'http://sedovin.com/', 'DOMICILIACIÓN BANCARIA', 'POLIGONO LA PUEBLA DE ALFINDEN CALLE K 29', 50171, 'La Puebla', 'Zaragoza', 'España', 0, '976109983', '626113938', '', 'ES8921004616162200028867', 'Normal', 'SEDOVIN', 'B50061019', 'POLIGONO LA PUEBLA DE ALFINDEN CALLE K 29', 50171, 'La Puebla', 'Zaragoza', 'España', NULL, ''),
(105, '', 1, 'Cristina', 'Gallart', 'cgallart@fribin.com', 'http://www.fribin.com/', 'PAGARÉ', 'Partida Chubera', 22500, 'Binéfar', 'Huesca', 'España', 0, '974431500', '677901690', '', 'PAGARÉ', 'Reducida', 'FRIBIN', 'F22004311', 'Partida Chubera', 22500, 'Binéfar', 'Huesca', 'España', 0, ''),
(106, '72978948V', 1, 'Elena', 'Polo Cantero', 'elenapolobeautycoach@gmail.com', 'https://www.el-tocador-de-elena.es/', 'DOMICILIACIÓN BANCARIA', 'Federico Mayo 10', 44769, 'Utrillas', 'Teruel', 'España', 0, '', '649790392', '', 'ES1720854061740330078361', 'Reducida', 'EL TOCADOR DE ELENA', '72978948V', 'Federico Mayo 10', 44769, 'Utrillas', 'Teruel', 'España', 0, ''),
(107, '73104503S', 0, 'Sonia', 'Montero Lamata', 'studiomostaza@gmail.com', '', '', 'Marques de Lema 42', 44550, 'Alcorisa', 'Teruel', 'España', 0, '', '656326512', '', 'ES8800492315522414048700', 'Normal', 'STUDIO MOSTAZA', '73104503S', 'Marques de Lema 42', 44550, 'Alcorisa', 'Teruel', 'España', NULL, 'http://www.instagram.com/studio_mostaza'),
(108, '', 1, 'Paula', 'Garfella Lorente', 'comex@tomasdetierra.com', 'http://www.tomasdetierra.com/', 'DOMICILIACIÓN BANCARIA', 'P.I Malpica C/E Parcelas 32-39 Nave 6', 500016, 'Zaragoza', 'Zaragoza', 'España', 0, '', '650743989', '', 'ES8901280406300109147451', 'Normal', 'COMEX', 'B50541796', 'P.I Malpica C/E Parcelas 32-39 Nave 6', 500016, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(109, '16013271G', 1, 'Teresa', 'Santafé', 't.santafe@interlink-idiomas.com', 'http://www.interlink-idiomas.com/', 'DOMICILIACIÓN BANCARIA', 'Breton 11, 6 Centro', 50005, 'Zaragoza', 'Zaragoza', 'España', 0, '976569358', '649540408', '', 'ES3720389951713000103733', 'Normal', 'INTERLINK', '16013271G', 'Breton 11, 6 Centro', 50005, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(110, '', 1, 'Mª Ángeles', 'Ladrón Jiménez', 'mladron@grb.es', 'http://www.grb.es/', 'DOMICILIACIÓN BANCARIA', 'Calle Alaún 19. Pla-Za', 50198, 'Zaragoza', 'Zaragoza', 'España', 0, '976504170', '618383043', '', 'ES3021005687720200004646', 'Normal', 'GRIFERIAS GROBER', 'B50667690', 'Calle Alaún 19. Pla-Za', 50198, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(111, '33487992S', 1, 'Carmen', 'Hurtado Follana', 'carmenhurtado@live.com', 'http://opticabajoaragon.com/', 'DOMICILIACIÓN BANCARIA', 'Calle La Fuente 19', 44500, 'Andorra', 'Teruel', 'España', 0, '978842004', '615394169', '', 'ES3500492313612614296016', 'Reducida', 'OPTICA BAJO ARAGON', '33487992S', 'Calle La Fuente 19', 44500, 'Andorra', 'Teruel', 'España', 0, ''),
(112, '17741619V', 1, 'Ana María', 'Marco Salvador', 'anamariamarco@reicaz.com', '', 'DOMICILIACIÓN BANCARIA', 'Torrenueva 31', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '976901037', '670221026', '', 'ES0421005449140200005209', 'Normal', 'ABOGADA / DESPACHO JURÍDICO', '17741619V', 'Torrenueva 31', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(113, '', 1, 'Yolanda', 'Regalado Artal', 'yolanda.regalado@ocgcontroller.com', 'http://www.ocgcontroller.com/', 'DOMICILIACIÓN BANCARIA', 'C/ Bilbao 2, 3ºD', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976232300', '615376137', '', 'ES1120850103920331740284', 'Normal', 'ORGANIZACIÓN, COSTS Y GESTIÓN S.L.P ', 'B99242406', 'C/ Bilbao 2, 3ºD', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(114, '', 1, 'Teresa', 'Fernández Fortún', 'mtfernandez@ibercaja.es', '', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', '', 'España', 0, '976.76.76.96', '606.96.73.60', '', '', 'Normal', 'IBERCAJA / FUNDACION IBERCAJA', '', '', 0, 'Zaragoza', '', 'España', NULL, ''),
(115, '', 1, 'Rosario', 'Guillén Mateo', 'charoguillenmateo@gmail.com  ', '', 'DOMICILIACIÓN BANCARIA', '', 0, '', '', 'España', 0, '', '', '', 'ES7000815351650001270930', 'Normal', 'MERCABARRIO', '', '', 0, '', '', 'España', NULL, ''),
(116, '', 1, 'Beatriz', 'López Palacín', 'blopez@lopezsoriano.com', 'http://www.grupoilssa.com/', 'DOMICILIACIÓN BANCARIA', 'CARRETERA DE CASTELLÓN 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976415200', '659449103', '', 'ES2500610384110000530114', 'Normal', 'INDUSTRIAS LOPEZ SORIANO', 'B50032002', 'CARRETERA DE CASTELLÓN 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(117, '', 1, 'Sofía', 'López Palacín', 'slopez@lopezsoriano.com', 'http://www.grupoilssa.com/', 'DOMICILIACIÓN BANCARIA', 'CARRETERA DE CASTELLÓN 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976415200', '696400888', '', 'ES2500610384110000530114', 'Normal', 'INDUSTRIAS LOPEZ SORIANO', 'B50032002', 'CARRETERA DE CASTELLÓN 58, KM 2,8', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(118, '29127244k', 1, 'Smara', 'Dávila Ballesteros', 'smaradavila@reicaz.com', 'https://smaradavila.com/', 'DOMICILIACIÓN BANCARIA', 'Johann Sebastian Bach nº 35 3º A', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '', '625067845', '', 'ES1221002431010200329553', 'Normal', 'SMARA DAVILA BALLESTEROS', '29127244k', 'Johann Sebastian Bach nº 35 3º A', 50012, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(119, '17862956Y', 1, 'Pilar', 'Martín Guillén', 'pmartin@grupmontaner.com', 'https://www.grupmontaner.com/', 'DOMICILIACIÓN BANCARIA', 'CALLE JERÓNIMO ZURITA Nº8, 1º', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976158092', '656318856', '', 'ES9120850138380330274264', 'Normal', 'GRUPO MONTANER ASOCIADOS', '17862956Y', 'CALLE JERÓNIMO ZURITA Nº8, 1º', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(120, '25456293P', 1, 'Marta', 'Muñoz Benedi', 'mmunoz@stpeuropa.eu', 'http://www.stpeuropa.eu/', 'DOMICILIACIÓN BANCARIA', 'Paseo Independencia 22 7ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '', '699291736', '', 'ES8020858244040330215566', 'Normal', 'STPEUROPA', '25456293P', 'Paseo Independencia 22 7ª Planta', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(121, '17731994Y', 1, 'Susana María', 'Pardos Martínez', 'susanapardos@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'CALLE MARQUÉS DE AHUMADA, 1-3 4ºT', 50007, 'Zaragoza', 'Zaragoza', 'España', 0, '', '679323747', '', 'ES3200495485532316289856', 'Normal', 'SOLER LIMPIEZAS', '17731994Y', 'CALLE MARQUÉS DE AHUMADA, 1-3 4ºT', 50007, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(122, '', 1, 'Maier', 'Escudero Dávila', 'mescudero@gruposifu.com', 'http://www.gruposifu.com/', 'TRANSFERENCIA', 'C/ Angela Bravo Ortega, 19, bajos', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, '', '626000488', '', 'TRANSFERENCIA', 'Normal', 'Servicios Integrales de Fincas de Aragón, S.L. (SIFA, S.L) ', 'B50856160', 'C/ Angela Bravo Ortega, 19, bajos', 50011, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(123, '05421959P', 1, 'Cristina', 'Luque Calvo', 'cluque@alsa.es', '', 'DOMICILIACIÓN BANCARIA', 'AVDA MANUEL RODRIGUEZ AYUSO 110', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '', '615162250', '', 'ES4120858425879400018498', 'Normal', 'Agreda Bus, S.L.', 'B99563264', 'AVDA MANUEL RODRIGUEZ AYUSO 110', 50012, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(124, '33451648B', 1, 'Gemma', 'Bonet Ramón', 'gebonet@gruposantander.es', 'http://www.santander.es/', 'TRANSFERENCIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '', '615904455', '', 'TRANSFERENCIA', 'Normal', 'BANCO SANTANDER, S.A.', '33451648B', '', 0, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(125, '18440260x', 1, 'Cristina', 'Griñón Egea', 'cgrinon@yahoo.com', '', 'DOMICILIACIÓN BANCARIA', 'Calle Mariana Pineda 16 3ºD', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '', '676974962', '', 'ES6014650100991713207611', 'Normal', 'PSICOLOGA', '18440260x', 'Calle Mariana Pineda 16 3ºD', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(126, '17739481H', 1, 'Maribel', 'Cros Serrano', 'peluqueriamaribelcros@hotmail.com', 'https://www.peluqueriamaribelcros.com/', 'DOMICILIACIÓN BANCARIA', 'Gertrudis Gómes Avellaneda 57 Local C Peluquería', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '876283242', '626057941', '', 'ES6030350314793140015462', 'Normal', 'PELUQUERÍA MARIBEL CROS', '17739481H', 'Gertrudis Gómes Avellaneda 57 Local C Peluquería', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(127, '73258281S', 1, 'Ana Isabel', 'Lizana Gil', 'ana@vistedecoratucasa.com', 'http://www.vistedecoratucasa.com/', 'DOMICILIACIÓN BANCARIA', 'P.T. TECHNOPARK, EDIF. DR. JOAQUIN REPOLLES ESPACIO COWORKING', 44600, 'Alcañiz', 'Teruel', 'España', 0, '', '670466633', '', 'ES5701820751520201579379', 'Reducida', 'VISTEDECORATUCASA', '73258281S', 'P.T. TECHNOPARK, EDIF. DR. JOAQUIN REPOLLES ESPACIO COWORKING', 44600, 'Alcañiz', 'Teruel', 'España', 0, ''),
(128, '', 1, 'Ana', 'Castel de las Heras', 'hello@thejollyguest.com', 'https://www.estiloybelleza.com/ ', 'DOMICILIACIÓN BANCARIA', 'Calle Leon Felipe 24 2º 3ª', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '', '645796879', '', 'ES8200811907180001234533', 'Normal', 'ESTILO Y BELLEZA / THE JOLLY GUEST', '', 'Calle Leon Felipe 24 2º 3ª', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(129, '25161190H', 0, 'Beatriz', 'Lucea', 'info@luceavalero.com', '', '', 'Torrecilla de Valmadrid, Casa 11. Urb. Nidalia', 50420, 'Cadrete', 'Zaragoza', 'España', 0, '', '667732366', '', 'ES6614650230471714193533', 'Normal', 'LUCEA VALERO', '25161190H', 'Torrecilla de Valmadrid, Casa 11. Urb. Nidalia', 50420, 'Cadrete', 'Zaragoza', 'España', NULL, ''),
(130, '17749505Z', 1, 'Ana María', 'Revilla', 'anarevilla@themoderncultural.com', '', 'DOMICILIACIÓN BANCARIA', 'Espoz y Mina 4', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '', '639306394', '', 'ES6100810170190002152420', 'Normal', 'THE MODERN CULTURAL PRODUCTIONS', '17749505Z', 'Espoz y Mina 4', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(131, '18457121N', 1, 'Leticia', 'Sánchez Álvarez', 'lsanchezalvarez7@gmail.com', 'https://www.letstat.es/', 'DOMICILIACIÓN BANCARIA', 'Plaza Justicia 12, 1ºB', 50650, 'Gallur', 'Zaragoza', 'España', 0, '', '644181166', '', 'ES3920853954410330280872', 'Normal', 'LETSTAT', '18457121N', 'Plaza Justicia 12, 1ºB', 50650, 'Gallur', 'Zaragoza', 'España', NULL, ''),
(132, '25437440S', 1, 'Carmen', 'Lozano Sabirón', 'carmen@adostorres.com', 'http://apartamentosdostorres.com/', 'DOMICILIACIÓN BANCARIA', 'Avenida Cesareo Alierta 23-25, Piso 1 Puerta 104', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '', '645256783', '', 'ES3631910133176225772125', 'Normal', 'CENTRO DE NEGOCIOS ZOSE', '25437440S', 'Avenida Cesareo Alierta 23-25, Piso 1 Puerta 104', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(133, '', 1, 'Blanca', 'Lacau Sanz', 'blanca.lacau@aquaservice.com', 'www.aquaservice.com', 'DOMICILIACIÓN BANCARIA', '', 22588, 'Graus', 'Huesca', 'España', 0, '', '650104710', '', 'ES2700492346172194416743', 'Reducida', 'Viva Aquaservice, S.L.', '', '', 22588, 'Graus', 'Huesca', 'España', 0, ''),
(134, '', 1, 'Guadalupe', 'del Buey Sayas', 'gdelbuey@certest.es', 'www.certest.es', 'DOMICILIACIÓN BANCARIA', 'c/ San Blas 106 5 ', 50003, 'Zaragoza', 'Zaragoza', 'España', 0, '', '669390256', '', 'ES0914650100931703532297', 'Normal', 'CERTEST BIOTEC S.L.', '', 'c/ San Blas 106 5 ', 50003, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(135, '73089407F', 1, 'Andrea', 'Lacueva', 'ofitecalacueva@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'ED. DR. JOAQUIN REPOLLES - PARQUE TECNOLÓGICO TECHNOPARK - OFICINA 6', 44600, 'Alcañiz', 'Teruel', 'España', 0, '', '680178299', '', 'ES4530800067982161531914', 'Reducida', 'Andrea Lacueva Laborda', '73089407F', 'ED. DR. JOAQUIN REPOLLES - PARQUE TECNOLÓGICO TECHNOPARK - OFICINA 6', 44600, 'Alcañiz', 'Teruel', 'España', 0, ''),
(136, '', 1, 'Sonia', 'Guerra Aznar', ' soniaguerra@sectorzaragoza.com', 'http://www.masquedxt.com', 'DOMICILIACIÓN BANCARIA', 'C/OVIEDO 5-7 LOCAL', 50007, 'Zaragoza', 'Zaragoza', 'España', 0, '976978890', '600496783', '', 'ES4700495480262316148181', 'Normal', 'MAS QUE DXT ARAGON', 'B50950518', 'C/OVIEDO 5-7 LOCAL', 50007, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(137, '', 1, 'Laura', 'Fuentes Piqueras', 'laura.fuentes@disaragon.es', 'http://Disaragon.es', 'DOMICILIACIÓN BANCARIA', 'CTRA CASTELLON, KM 3800', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976590313', '635639554', '', 'ES2801823131400010303201', 'Normal', 'DISARAGON', 'B50735802', 'CTRA CASTELLON, KM 3800', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(138, '', 1, 'Paula', 'Yago Aznar', 'p.yago@bodegastempore.com', 'http://www.bodegastempore.com', 'DOMICILIACIÓN BANCARIA', 'CTRA. ZARAGOZA S/N', 50131, 'LECERA', 'Zaragoza', 'España', 0, '976835040', '619743221', '', 'ES8801826900240201703811', 'Reducida', 'BODEGAS TEMPORE', 'B50906734', 'CTRA. ZARAGOZA S/N', 50131, 'LECERA', 'Zaragoza', 'España', 0, ''),
(139, '', 1, 'Rosa', 'Palacín Villa', 'rosapalacin49@gmail.com ', 'www.grupoilssa.com', 'DOMICILIACIÓN BANCARIA', 'Ctra. Castellón 58, KM 2, 800', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, '976415200', '659449102', '', 'ES2500610384110000530114', 'Normal', 'INDUSTRIAS LOPEZ SORIANO', 'B 50032002', 'Ctra. Castellón 58, KM 2, 800', 50013, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(140, '', 1, 'Gema', 'Rubio Peinado', 'gema@rubiomorte.com', 'http://www.rubiomorte.com', 'DOMICILIACIÓN BANCARIA', 'ALEJANDRO OLIVAN 20-22, LOCAL', 50011, 'Zaragoza', 'Zaragoza', 'España', 0, '', '670732784', '', 'ES3020855201210331102267', 'Normal', 'CONSTRUCCIONES RUBIO MORTE, S.A.', 'A50070663', 'ALEJANDRO OLIVAN 20-22, LOCAL', 50011, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(141, '17749624H', 1, 'Arancha', 'Bauto Ortega', 'ed4.project@gmail.com', 'http://www.muninteriorismo.com', 'DOMICILIACIÓN BANCARIA', 'Av. Pablo Ruiz Picasso4, local', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, '876285418', '663382267', '', 'ES6100811146750001023206', 'Normal', 'PROJECT TEMPUS SERVICIOS INTEGRALES S.L ', '17749624H', 'Av. Pablo Ruiz Picasso4, local', 50018, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(142, '', 1, 'Ana', 'Chico Candial', 'anachico@biosalud.org', 'http://www.biosalud.org', 'DOMICILIACIÓN BANCARIA', 'Residencial Paraíso, 9', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976221133', '630015313', '', 'ES8321003386812200099997', 'Normal', 'Biosalud DAY Hospital', 'B50107051', 'Residencial Paraíso, 9', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(143, '', 1, 'Sara', 'Santolaria Sampietro', 'ssantolaria@fundacionhiberus.com', 'http://www.fundacionhiberus.com', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '', '640296183', '', '', 'Normal', 'Fundación Hiberus', '', '', 0, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(144, '73022495W', 1, 'Ana', 'Lázaro Palacios', 'holaanalazaro@gmail.com', 'https://www.linkedin.com/in/analazarocommunitymanager/', 'DOMICILIACIÓN BANCARIA', 'Calle Mayor 105', 50360, 'Daroca', 'Zaragoza', 'España', 0, '', '664190041', '', 'ES8400495527402716033187', 'Reducida', 'Ana Lazaro', '73022495W', 'Calle Mayor 105', 50360, 'Daroca', 'Zaragoza', 'España', 0, ''),
(145, '25170886P', 1, 'María de las Mercedes', 'Penacho Gómez', 'mercedes@virtuscomunicacion.com', 'https://www.virtuscomunicacion.com/', 'DOMICILIACIÓN BANCARIA', '', 0, 'Zaragoza', 'Zaragoza', 'España', 0, '', '658501904', '', 'ES4921009096552200114561', 'Normal', 'Virtus comunicación', '25170886P', '', 0, 'Zaragoza', 'Zaragoza', 'España', 62, ''),
(146, '', 1, 'María', 'Rubio', 'mrubio@zeumat.com', 'http://www.zeumat.com', 'DOMICILIACIÓN BANCARIA', 'RODRIGO DIAZ DE VIVAR 6 LOCAL', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '', '665783914', '', 'ES3320850171040330124392', 'Normal', 'Zeumat-Zesis', 'B50988906', 'RODRIGO DIAZ DE VIVAR 6 LOCAL', 50006, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(147, '76921782F', 1, 'Pilar', 'Vilella', 'pilar@vilellayasociados.com', ' https://www.vilellayasociados.com/', 'DOMICILIACIÓN BANCARIA', 'Calle San Vicente Martir 11 escalera A, 2 izquierda', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '', '687862446', '', 'ES7300496992912110002128', 'Normal', 'Vilella y Asociados', '76921782F', 'Calle San Vicente Martir 11 escalera A, 2 izquierda', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(148, '', 1, 'Anabel', 'Costas', 'anabelcostas@elprivilegio.com', 'http://www.elprivilegio.com', 'DOMICILIACIÓN BANCARIA', 'ZACALERA, 1', 22663, 'Tramacastilla de Tena', 'Huesca', 'España', 0, '974487206', '639665010', '', 'ES6800811903320001023712', 'Reducida', 'Hotel El privilegio', 'B22383715', 'ZACALERA, 1', 22663, 'Tramacastilla de Tena', 'Huesca', 'España', 0, ''),
(149, '25190856Z', 1, 'Raquel', 'Serrano Borraz', 'raquel.serrano@gmail.com', 'https://www.raquelserrano.me/   ', 'DOMICILIACIÓN BANCARIA', 'Schleissheimerstrasse 232a, Muchich, Alemania', 80797, 'Munich', 'Munich', 'Alemania', 0, '+4917624340896', '628424124', '', 'DE32200411550576536700', 'Normal', 'Raquel Serrano', '25190856Z', 'Schleissheimerstrasse 232a, Muchich, Alemania', 80797, 'Munich', 'Munich', 'Alemania', 31, ''),
(150, '25176371L', 1, 'Ana', 'Miranda', 'info@anamirandaestudio.com', 'https://linktr.ee/AMirandaestudio', 'DOMICILIACIÓN BANCARIA', 'Don Jaime I, nº14, 3ºcentro', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '', '615005553', '', '', 'Normal', 'Ana Miranda Estudio', '25176371L', 'Don Jaime I, nº14, 3ºcentro', 50001, 'Zaragoza', 'Zaragoza', 'España', 16, ''),
(151, '25427793M', 1, 'Belén', 'Mayoral Palacios', 'bm@bmregalosdeempresa.com', 'http://www.bmregalosdeempresa.com', 'DOMICILIACIÓN BANCARIA', 'Poligono Empresarium, calle retama 25 nave B7', 50720, 'Zaragoza', 'Zaragoza', 'España', 0, '876262641', '608400707', '', 'ES4720850157340330327167', 'Normal', 'BM Regalos de Empresa', '25427793M', 'Poligono Empresarium, calle retama 25 nave B7', 50720, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(152, '18424762Z', 1, 'Pego', 'Punter Placencia', 'p.punter@bthetravelbrand.com', ' http://premium.bthetravelbrand.com', 'TRANSFERENCIA', 'San Clemtente 15', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '876258456', '676242756', '', 'TRANSFERENCIA', 'Normal', 'B the travel brand & Catai', '18424762Z', 'San Clemtente 15', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(153, 'X6741376F', 1, 'Katerina', 'Kokurina', 'katerina@panelsandwich.com', 'http://www.pnaelsandwich.com', 'DOMICILIACIÓN BANCARIA', 'Coso 46', 500004, 'Zaragoza', 'Zaragoza', 'España', 0, '976900043', '626488241', '', 'ES5300492813412894925555', 'Normal', 'Panel Sandwich Group SL', 'X6741376F', 'Coso 46', 500004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(154, '', 1, 'Carlota', 'Ruiz', 'limpiberica@gmail.com', 'https://www.limpiberica.com/', 'DOMICILIACIÓN BANCARIA', 'C/ DOCTOR CASAS, 20 (CENTRO DE NEGOCIOS', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '', '696434946', '', 'ES3120855217790331959288', 'Normal', 'Limpiberica', '', 'C/ DOCTOR CASAS, 20 (CENTRO DE NEGOCIOS', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(155, '17733170D', 1, 'Reyes', 'Gargallo', 'reyes@rgmarketing.es', 'http://rgmarketing.es', 'DOMICILIACIÓN BANCARIA', 'Paseo de la Mina 7', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '', '670218063', '', 'ES9020858242100330180854', 'Normal', 'RG', '17733170D', 'Paseo de la Mina 7', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(156, '29085296W', 1, 'Susana', 'Usieto Pallás', 'susanausietodeco@gmail.com ', '', 'TRANSFERENCIA', 'Brazato 1, local', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '', '639383286', '', 'TRANSFERENCIA', 'Normal', 'SUSANA USIETO DECO ', '29085296W', 'Brazato 1, local', 50012, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(157, '48815021M', 1, 'Ligia', 'López Ballester', 'edaproject.irc@gmail.com', 'http://www.edaproject.es', 'TRANSFERENCIA', 'Calle Rafael Gasset, Nº 23. Tercero', 22800, 'Ayerbe', 'Huesca', 'España', 0, '', '652516212', '', 'TRANSFERENCIA', 'Normal', 'EDA Project', '48815021M', 'Calle Rafael Gasset, Nº 23. Tercero', 22800, 'Ayerbe', 'Huesca', 'España', NULL, ''),
(158, '72888018Y', 1, 'Miriam', 'Chueca', 'hola@miriamchueca.es', 'miriamchueca.es', 'DOMICILIACIÓN BANCARIA', 'C/ Emilio Alfaro Lapuerta, 1, 1º primera', 50017, 'Zaragoza', 'Zaragoza', 'España', 0, '', '687608976', '', 'ES3230170552572409826829', 'Normal', 'Miriam Chueca', '72888018Y', 'C/ Emilio Alfaro Lapuerta, 1, 1º primera', 50017, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(159, '25435081W', 1, 'Carmen', 'Arroyo Gómez', 'carroyo@landainmobiliaria.com', 'http://www. landapropiedades.com', 'DOMICILIACIÓN BANCARIA', 'Avda Gómez Laguna, 19. 11B', 50009, 'Zaragoza', 'Zaragoza', 'España', 0, '976301480', '610917594', '', 'ES0231910028335633960124', 'Normal', 'Landa Propiedaes', '25435081W', 'Avda Gómez Laguna, 19. 11B', 50009, 'Zaragoza', 'Zaragoza', 'España', 81, ''),
(160, '29125397z', 1, 'María Pía', 'Pablos Abiol', 'piadepablos@gmail.com', 'http://www.biointeriors.com', 'DOMICILIACIÓN BANCARIA', 'Zurita 17, Pral Izda', 50001, 'Zaragoza', 'Zaragoza', 'España', 0, '976427022', '687458882', '', 'ES1301826900200201991313', 'Normal', 'María Pía Pablos Abiol', '29125397z', 'Zurita 17, Pral Izda', 50001, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(161, '', 1, 'Begoña', 'Pérez Espes', 'bego@nanafood.es', 'http://www.nanafood.es', 'DOMICILIACIÓN BANCARIA', 'CALLE NOGAL, 47. Polígono Malpica-Alfindén.', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', 0, '', '669056975', '', 'ES2720850152010330553270', 'Normal', 'NANA FOOD sl', 'B99446056', 'CALLE NOGAL, 47. Polígono Malpica-Alfindén.', 50171, 'La Puebla de Alfindém', 'Zaragoza', 'España', NULL, ''),
(162, '17736691B', 1, 'Nati', 'Hueso Escribano', 'natihueso@natihueso.com', '', 'DOMICILIACIÓN BANCARIA', 'c/Mayoral 4, 6d', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '', '678601457', '', 'ES4201280400640100208963', 'Normal', 'Nati Hueso', '17736691B', 'c/Mayoral 4, 6d', 50004, 'Zaragoza', 'Zaragoza', 'España', 4, ''),
(163, '76917174E', 1, 'Elisa', 'Pelayo Astiz', 'informacion@somosagilmente.com', '', 'DOMICILIACIÓN BANCARIA', 'casa 17', 50540, 'Zaragoza', 'Borja', 'España', 0, '976868616', '682783432', '', 'ES0320850131150330116208', 'Normal', 'Agilmente', '76917174E', 'casa 17', 50540, 'Zaragoza', 'Borja', 'España', NULL, ''),
(164, '50444522W', 1, 'Amelia', 'Cantarero García', 'ameliacantarerogarcia@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Doctor Casas nº20, 2ºD', 50008, 'Zaragoza', 'Zaragoza', 'España', 0, '976234128', '687552020', '', 'ES6300815543540001370247', 'Normal', 'App Saludenconductores sl', '50444522W', 'Doctor Casas nº20, 2ºD', 50008, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(165, '17759642P', 1, 'Alejandra', 'Reguero López', 'alejandra.reguero@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Rosales del Canal', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '', '696238885', '', 'ES5300496622712516107036', 'Normal', 'Pequeños Maestros', 'B99433435', 'Rosales del Canal', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, 'Gerente de Pequeños Maestros'),
(166, '17719934K', 1, 'María', 'Felipe Caparrós', 'mfelipecaparros@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Mariano Royo 9-11 5º-6º', 50006, 'Zaragoza', 'Zaragoza', 'España', 0, '', '650465701', '', 'ES5431835000801014903528', 'Normal', 'Arellano y Felipe Arqtos', '17719934K', 'Mariano Royo 9-11 5º-6º', 50006, 'Zaragoza', 'Zaragoza', 'España', 17, ''),
(167, '', 1, 'Elena', 'Esteban Pintado', 'elenaestebanpintado@gmail.com', '', 'DOMICILIACIÓN BANCARIA', 'Avda. Ilustración, 24 casa 78', 50012, 'Zaragoza', 'Zaragoza', 'España', 0, '976458075', '630927141', '', 'ES8520855240710331036349', 'Normal', 'Zarasport2021 sl', 'B16776312', 'Avda. Ilustración, 24 casa 78', 50012, 'Zaragoza', 'Zaragoza', 'España', 17, ''),
(168, '17741607M', 1, 'Belén', 'Rivas Lorenz', 'abrivas1@yahoo.es', 'http://www.conbedebelen.es', 'DOMICILIACIÓN BANCARIA', 'Alemania 7-17', 50410, 'Cuarte de Huerva', 'Zaragoza', 'España', 0, '', '610255334', '', 'ES7701280401000100057092', 'Normal', 'Qwuerty Copywriting sl', '17741607M', 'Alemania 7-17', 50410, 'Cuarte de Huerva', 'Zaragoza', 'España', NULL, ''),
(169, '17162856A', 1, 'Gloria', 'Otín', 'gloria@customsborders.com', 'http://www.customsborders.com', 'DOMICILIACIÓN BANCARIA', 'PLAZA MUESTRA SRA. DEL CARMEN Nº 11, 1ºC', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976227236', '616971166', '', '', 'Normal', 'CUSTOMS & BORDERS, S.L.', '17162856A', 'PLAZA MUESTRA SRA. DEL CARMEN Nº 11, 1ºC', 50004, 'Zaragoza', 'Zaragoza', 'España', NULL, ''),
(170, '29093949F', 1, 'Mª Teresa', 'García Hidalgo', 'maigarh7@gmail.com', 'https://alpargatashidalgo.es/', 'DOMICILIACIÓN BANCARIA', 'Calle Fueros de Aragón, 4 - casa 2', 50690, 'Pedrola', 'Zaragoza', 'España', 0, '', '689555170', '', 'ES1201825297210200315635', 'Normal', 'Hidalgo Alpargatas ', '29093949F', 'Calle Fueros de Aragón, 4 - casa 2', 50690, 'Pedrola', 'Zaragoza', 'España', NULL, ''),
(171, '72997813E', 1, 'Esther', 'Canales', 'info@esthercanales.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ San Antonio María Claret 13, 1H', 50005, 'Zaragoza', 'Zaragoza', 'España', 0, '', '600681677', '', 'ES8900730100580588804860', 'Normal', 'Esther Canales', '72997813E', 'C/ San Antonio María Claret 13, 1H', 50005, 'Zaragoza', 'Zaragoza', 'España', 0, 'Estudio de diseño especializado en Branding y diseño web.'),
(172, '25136870', 1, 'Sagrario', 'Valero Bielsa', '', '', '', '', 0, '', '', '', 0, '', '', '', '', 'Normal', 'Valero Bielsa abogados', 'B99278160', 'Camino Cabaldos 60', 50013, 'Zaragoza', 'Zaragoza', 'España', 0, ''),
(173, '25197265Y', 1, 'Paula', 'Jiménez Carbó', 'paula@jimenezcarbo.com', '', 'DOMICILIACIÓN BANCARIA', 'C/ Cinco de Marzo 18, Planta 2, Oficina 1', 50004, 'Zaragoza', 'Zaragoza', 'España', 0, '976925310', '608088910', '', 'ES2920850111720331415396', 'Normal', 'Jiménez Carbó Digital SL', 'B72946148', 'Calle Cinco de Marzo 18, piso 2, oficina 1', 50004, 'Zaragoza', 'Zaragoza', 'España', 5, 'Directora de Jiménez Carbó Digital SL'),
(174, '73008625R', 1, 'Freyja', 'Pérez Keller', 'fperez@snailstep.com', '', 'DOMICILIACIÓN BANCARIA', '', 0, '', '', '', 0, '', '608048892', '', 'ES0401826440310201602850', 'Normal', 'SNAILSTEP', 'B09699505', 'CEEIARAGON', 50018, 'Zaragoza', 'Zaragoza', 'España', 0, 'CEO / Fundadora SNAILSTEP'),
(175, '17441138P', 1, 'Pilar', 'Barcelona de Pedro', 'pbarcelona@renta4.es', '', 'DOMICILIACIÓN BANCARIA', 'Paseo de la Habana 74', 28036, 'Madrid', 'Madrid', 'España', 0, '976206093', '669751667', '', '', 'Normal', 'RENTA 4 BANCO', 'A82473018', 'Paseo de la Habana 74', 28036, 'Madrid', 'Madrid', 'España', 0, 'Directora Territorial Renta 4 Banco');

DROP TABLE IF EXISTS `socias_pertenecen_empresas`;
CREATE TABLE IF NOT EXISTS `socias_pertenecen_empresas` (
  `sociasoc_cod` int(5) NOT NULL,
  `Empresaemp_cif` varchar(10) NOT NULL,
  PRIMARY KEY (`sociasoc_cod`,`Empresaemp_cif`),
  KEY `Empresaemp_cif` (`Empresaemp_cif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `socias_pertenecen_empresas` (`sociasoc_cod`, `Empresaemp_cif`) VALUES
(3, '17220134B'),
(4, '25458719C'),
(5, 'B50670264'),
(6, 'B99407173'),
(7, 'B99362972'),
(8, '25147473D'),
(11, 'B99124588'),
(14, '17721834N'),
(15, 'B50633759'),
(16, 'B50799790'),
(17, 'B50032002'),
(18, 'B50921295'),
(19, 'B50909423'),
(20, 'B50670843'),
(21, 'B22146005'),
(22, 'G50107531'),
(23, 'A87456372'),
(25, 'M666789471'),
(26, 'B99080772'),
(27, 'B50500263'),
(28, 'B99050031'),
(29, 'B99254302'),
(30, 'B99291528'),
(31, 'B09979865'),
(33, 'A50081546'),
(34, 'A50054313'),
(39, 'B99393704'),
(40, 'G50689413'),
(41, '29104356H'),
(42, '18019709Z'),
(43, 'A50013465'),
(45, 'G99338980'),
(46, 'B99391849'),
(47, 'B50501097'),
(48, 'B50766930'),
(49, '73076956E'),
(50, 'B50919893'),
(51, 'B99146458'),
(52, 'B99453722'),
(53, 'B99455180'),
(54, '25135411E'),
(55, 'B99413908'),
(56, 'B50776947'),
(58, 'A50130277'),
(59, 'B65295560'),
(60, 'A50089812'),
(61, 'B50792688'),
(62, 'B50566652'),
(63, '18935660J'),
(64, 'A50051218'),
(65, '18209654W'),
(66, 'B99423311'),
(67, 'B99468803'),
(68, 'B99137721'),
(69, '25145357D'),
(70, 'Q5018001G'),
(72, 'G99359788'),
(73, '29123690D'),
(74, '25467068L '),
(75, 'B86511722'),
(76, 'B99320327'),
(77, 'B50152149'),
(78, 'G99466864'),
(79, 'F99374670'),
(80, 'B22210843'),
(82, 'G99380842'),
(83, 'G99380842'),
(85, '25152771V'),
(88, '29097971G'),
(89, '73078869A'),
(90, '25460196R'),
(95, 'B99386584'),
(98, '254663468F'),
(99, '17729629X'),
(100, 'B93661726'),
(101, '16802548J'),
(103, 'B99472151'),
(104, 'B50061019'),
(106, '72978948V'),
(107, '73104503S'),
(108, 'B50541796'),
(109, '16013271G'),
(110, 'B50667690'),
(112, '17741619V'),
(113, 'B99242406'),
(116, 'B50032002'),
(117, 'B50032002'),
(118, '29127244k'),
(119, '17862956Y'),
(120, '25456293P'),
(121, '17731994Y'),
(122, 'B50856160'),
(123, 'A50007301'),
(123, 'B99563264'),
(124, '33451648B'),
(125, '18440260x'),
(126, '17739481H'),
(129, '25161190H'),
(130, '17749505Z'),
(131, '18457121N'),
(132, '25437440S'),
(136, 'B50950518'),
(137, 'B50735802'),
(139, 'B50032002'),
(140, 'A50070663'),
(141, '17749624H'),
(142, 'B50107051'),
(146, 'B50988906'),
(147, '76921782F'),
(151, '25427793M'),
(152, '18424762Z'),
(153, 'X6741376F'),
(155, '17733170D'),
(156, '29085296W'),
(157, '48815021M'),
(158, '72888018Y'),
(160, '29125397z'),
(161, 'B99446056'),
(163, '76917174E'),
(164, '50444522W'),
(165, 'B99433435'),
(168, '17741607M'),
(169, '17162856A'),
(170, '29093949F'),
(171, '72997813E'),
(172, 'B99278160'),
(173, 'B72946148'),
(174, 'B09699505'),
(175, 'A82473018');

DROP TABLE IF EXISTS `socia_referida_por_socia`;
CREATE TABLE IF NOT EXISTS `socia_referida_por_socia` (
  `srps_socia_referida` int(5) NOT NULL,
  `srps_socia_refiere` int(5) NOT NULL,
  PRIMARY KEY (`srps_socia_referida`),
  KEY `FKsocia_refe889116` (`srps_socia_refiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `usr_username` varchar(50) NOT NULL,
  `usr_pass` varchar(200) DEFAULT NULL,
  `usr_nombre` varchar(50) NOT NULL DEFAULT '',
  `usr_rol` tinyint(4) NOT NULL,
  `usr_socia` int(5) DEFAULT NULL,
  PRIMARY KEY (`usr_username`),
  KEY `FKUsuario9113` (`usr_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `usuario` (`usr_username`, `usr_pass`, `usr_nombre`, `usr_rol`, `usr_socia`) VALUES
('info@arteporcuatro.com', '0aa35071e420b7d2dfab8fd30cfb4d5fe6402dfe', 'Myriam Monterde', 20, NULL),
('marialopez@grupoilssa.com', '8301f624d7c056dcc421475c555cf56d8c39717c', 'María López', 20, NULL),
('secretaria@arame.org', '0c9c54793a2fa43a7f0cb1f8657b866fd5a0ce82', 'Administrador', 20, NULL),
('tomas@alcanalytics.com', '922c282e5b4f2d96d78126943166732ea7fbd4f5', 'SuperUsuario', 10, NULL);
DROP VIEW IF EXISTS `ver_roles`;
CREATE TABLE IF NOT EXISTS `ver_roles` (
`nivel` tinyint(4)
,`nombre` varchar(20)
);
DROP VIEW IF EXISTS `ver_socias_activas`;
CREATE TABLE IF NOT EXISTS `ver_socias_activas` (
`soc_cod` int(5)
,`soc_nif` varchar(10)
,`soc_alta` tinyint(1)
,`soc_nombre` varchar(50)
,`soc_apellidos` varchar(100)
,`soc_email` varchar(80)
,`soc_metodo_pago` varchar(50)
,`soc_dir` varchar(200)
,`soc_cp` int(5)
,`soc_poblacion` varchar(100)
,`soc_provincia` varchar(50)
,`soc_es_autonoma` tinyint(1)
,`soc_tlf` varchar(14)
,`soc_movil` varchar(14)
,`soc_fax` varchar(14)
,`soc_iban` varchar(24)
,`soc_cuota` varchar(100)
);
DROP VIEW IF EXISTS `ver_usuarios`;
CREATE TABLE IF NOT EXISTS `ver_usuarios` (
`rol_cod` tinyint(4)
,`nombre` varchar(50)
,`rol` varchar(20)
);
DROP TABLE IF EXISTS `consultar_correos_socias`;

DROP VIEW IF EXISTS `consultar_correos_socias`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `consultar_correos_socias`  AS SELECT `socia`.`soc_email` AS `email`, `socia`.`soc_cod` AS `socia` FROM `socia` ORDER BY `socia`.`soc_cod` ASC  ;
DROP TABLE IF EXISTS `consultar_empresas`;

DROP VIEW IF EXISTS `consultar_empresas`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `consultar_empresas`  AS SELECT `empresa`.`emp_cif` AS `nif`, `empresa`.`emp_nombre` AS `nombre`, `empresa`.`emp_dir` AS `dir`, `empresa`.`emp_cp` AS `cp`, `empresa`.`emp_poblacion` AS `poblacion`, `empresa`.`emp_provincia` AS `provincia`, `empresa`.`emp_pais` AS `pais`, `empresa`.`emp_iban` AS `iban`, `empresa`.`emp_email` AS `email`, `empresa`.`emp_tlf` AS `telefono`, `empresa`.`emp_tlf_2` AS `telefono_2`, `empresa`.`emp_fax` AS `fax`, `empresa`.`emp_num_trabajadores` AS `num_trabajadores`, `empresa`.`emp_year_fundacion` AS `year_fundacion`, `empresa`.`emp_descripcion` AS `descripcion`, `empresa`.`emp_es_autonoma` AS `es_autonoma`, `empresa`.`emp_notas` AS `notas` FROM `empresa` ORDER BY `empresa`.`emp_nombre` ASC  ;
DROP TABLE IF EXISTS `consultar_sectores`;

DROP VIEW IF EXISTS `consultar_sectores`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `consultar_sectores`  AS SELECT `sector`.`sect_nombre` AS `nombre` FROM `sector` ORDER BY `sector`.`sect_nombre` ASC  ;
DROP TABLE IF EXISTS `consultar_socias`;

DROP VIEW IF EXISTS `consultar_socias`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `consultar_socias`  AS SELECT `socia`.`soc_cod` AS `cod`, `socia`.`soc_nif` AS `nif`, `socia`.`soc_alta` AS `alta`, `socia`.`soc_nombre` AS `nombre`, `socia`.`soc_apellidos` AS `apellidos`, `socia`.`soc_email` AS `email`, `socia`.`soc_metodo_pago` AS `metodo_pago`, `socia`.`soc_dir` AS `dir`, `socia`.`soc_cp` AS `cp`, `socia`.`soc_poblacion` AS `poblacion`, `socia`.`soc_provincia` AS `provincia`, `socia`.`soc_pais` AS `pais`, `socia`.`soc_es_autonoma` AS `es_autonoma`, `socia`.`soc_tlf` AS `tlf`, `socia`.`soc_movil` AS `movil`, `socia`.`soc_fax` AS `fax`, `socia`.`soc_iban` AS `iban`, `socia`.`soc_cuota` AS `cuota`, `socia`.`soc_fact_nombre` AS `fact_nombre`, `socia`.`soc_fact_dir` AS `fact_dir`, `socia`.`soc_fact_cp` AS `fact_cp`, `socia`.`soc_fact_poblacion` AS `fact_poblacion`, `socia`.`soc_fact_provincia` AS `fact_provincia`, `socia`.`soc_fact_pais` AS `fact_pais`, `cuota`.`cuota_cuantia` AS `cuota_cuantia`, `socia`.`soc_notas` AS `notas` FROM (`socia` left join `cuota` on(`cuota`.`cuota_nombre` = `socia`.`soc_cuota`))  ;
DROP TABLE IF EXISTS `consultar_years_recibos`;

DROP VIEW IF EXISTS `consultar_years_recibos`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `consultar_years_recibos`  AS SELECT DISTINCT `recibo`.`rec_year` AS `year` FROM `recibo` ORDER BY `recibo`.`rec_year` ASC  ;
DROP TABLE IF EXISTS `ver_roles`;

DROP VIEW IF EXISTS `ver_roles`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `ver_roles`  AS SELECT `rol`.`rol_nivel` AS `nivel`, `rol`.`rol_nombre` AS `nombre` FROM `rol` ORDER BY `rol`.`rol_nivel` ASC  ;
DROP TABLE IF EXISTS `ver_socias_activas`;

DROP VIEW IF EXISTS `ver_socias_activas`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `ver_socias_activas`  AS SELECT `socia`.`soc_cod` AS `soc_cod`, `socia`.`soc_nif` AS `soc_nif`, `socia`.`soc_alta` AS `soc_alta`, `socia`.`soc_nombre` AS `soc_nombre`, `socia`.`soc_apellidos` AS `soc_apellidos`, `socia`.`soc_email` AS `soc_email`, `socia`.`soc_metodo_pago` AS `soc_metodo_pago`, `socia`.`soc_dir` AS `soc_dir`, `socia`.`soc_cp` AS `soc_cp`, `socia`.`soc_poblacion` AS `soc_poblacion`, `socia`.`soc_provincia` AS `soc_provincia`, `socia`.`soc_es_autonoma` AS `soc_es_autonoma`, `socia`.`soc_tlf` AS `soc_tlf`, `socia`.`soc_movil` AS `soc_movil`, `socia`.`soc_fax` AS `soc_fax`, `socia`.`soc_iban` AS `soc_iban`, `socia`.`soc_cuota` AS `soc_cuota` FROM `socia` WHERE `socia`.`soc_alta` = 1 ORDER BY `socia`.`soc_cod` ASC  ;
DROP TABLE IF EXISTS `ver_usuarios`;

DROP VIEW IF EXISTS `ver_usuarios`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`arame_db_admin`@`%` SQL SECURITY DEFINER VIEW `ver_usuarios`  AS SELECT `usuario`.`usr_rol` AS `rol_cod`, `usuario`.`usr_nombre` AS `nombre`, `rol`.`rol_nombre` AS `rol` FROM (`usuario` left join `rol` on(`usuario`.`usr_rol` = `rol`.`rol_nivel`)) ORDER BY `usuario`.`usr_rol` ASC, `usuario`.`usr_nombre` ASC  ;


ALTER TABLE `alta`
  ADD CONSTRAINT `FKAlta936803` FOREIGN KEY (`alta_socia`) REFERENCES `socia` (`soc_cod`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `baja`
  ADD CONSTRAINT `FKBaja631381` FOREIGN KEY (`baja_socia`) REFERENCES `socia` (`soc_cod`);

ALTER TABLE `empresas_pertenecen_sectores`
  ADD CONSTRAINT `FKEmpresas_p460830` FOREIGN KEY (`eps_empresa`) REFERENCES `empresa` (`emp_cif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKEmpresas_p718961` FOREIGN KEY (`eps_sector`) REFERENCES `sector` (`sect_nombre`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `premio`
  ADD CONSTRAINT `premio_ibfk_1` FOREIGN KEY (`premio_socia`) REFERENCES `socia` (`soc_cod`) ON UPDATE CASCADE;

ALTER TABLE `socia`
  ADD CONSTRAINT `FKsocia171277` FOREIGN KEY (`soc_cuota`) REFERENCES `cuota` (`cuota_nombre`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `socias_pertenecen_empresas`
  ADD CONSTRAINT `FKsocias_per296932` FOREIGN KEY (`Empresaemp_cif`) REFERENCES `empresa` (`emp_cif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKsocias_per560052` FOREIGN KEY (`sociasoc_cod`) REFERENCES `socia` (`soc_cod`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `socia_referida_por_socia`
  ADD CONSTRAINT `FKsocia_refe70733` FOREIGN KEY (`srps_socia_referida`) REFERENCES `socia` (`soc_cod`),
  ADD CONSTRAINT `FKsocia_refe889116` FOREIGN KEY (`srps_socia_refiere`) REFERENCES `socia` (`soc_cod`);

ALTER TABLE `usuario`
  ADD CONSTRAINT `FKUsuario9113` FOREIGN KEY (`usr_rol`) REFERENCES `rol` (`rol_nivel`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
