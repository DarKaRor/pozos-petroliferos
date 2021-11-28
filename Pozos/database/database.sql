CREATE DATABASE datapozos;
    USE datapozos;

DROP TABLE IF EXISTS `pozos`;

CREATE TABLE `pozos`(
    `id_pozo` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(30) NOT NULL,
    `profundidad` DOUBLE(10,2) NOT NULL,
    PRIMARY KEY (`id_pozo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `mediciones`(
    `id_medicion` INT(11) NOT NULL AUTO_INCREMENT,
    `id_pozo` INT(11) NOT NULL,
    `valor` DOUBLE(10,2) NOT NULL,
    `fecha` DATE,
    PRIMARY KEY (`id_medicion`),
    FOREIGN KEY (`id_pozo`) REFERENCES pozos(`id_pozo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;