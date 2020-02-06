/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS members
(
    `id`                 INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `jaarlidmaatschap`   INT(10),
    `voorletters`        varchar(30),
    `voornaam`           varchar(30),
    `achternaam`         varchar(30),
    `geboortedatum`      varchar(10),
    `adres`              varchar(50),
    `postcode`           varchar(6),
    `huisnummer`         varchar(6),
    `woonplaats`         varchar(30),
    `telefoon_vast`      varchar(10),
    `telefoon_mobiel`    varchar(10),
    `emailadres`         varchar(254),
    `ingangsdatum`       varchar(10),
    `geslacht`           varchar(50),
    `nieuwsbrief`        TINYINT,
    `vrijwilliger`       TINYINT,
    `vrijwilligeroptie1` TINYINT,
    `vrijwilligeroptie2` TINYINT,
    `vrijwilligeroptie3` TINYINT,
    `vrijwilligeroptie4` TINYINT,
    `vrijwilligeroptie5` TINYINT,
    `betalingswijze`     varchar(30),
    `iban`               varchar(30)        DEFAULT NULL,
    `machtigingskenmerk` varchar(30)        DEFAULT NULL,
    `incasso_gelukt`     varchar(30)        DEFAULT NULL,
    `opmerking`          varchar(30)        DEFAULT NULL,
    `CreationDate`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate`   timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);