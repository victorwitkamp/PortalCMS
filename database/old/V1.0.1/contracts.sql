CREATE TABLE IF NOT EXISTS band_contracts
(
    `id`                         INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `beuk_vertegenwoordiger`     varchar(50) NOT NULL,
    `band_naam`                  varchar(50) NOT NULL,
    `bandleider_naam`            varchar(50) NOT NULL,
    `bandleider_adres`           varchar(50) NOT NULL,
    `bandleider_postcode`        varchar(6)  NOT NULL,
    `bandleider_woonplaats`      varchar(30) NOT NULL,
    `bandleider_geboortedatum`   varchar(10) NOT NULL,
    `bandleider_telefoonnummer1` varchar(10) NOT NULL,
    `bandleider_telefoonnummer2` varchar(10) NOT NULL,
    `bandleider_email`           varchar(30) NOT NULL,
    `bandleider_bsn`             varchar(9)  NOT NULL,
    `huur_oefenruimte_nr`        varchar(1)  NOT NULL,
    `huur_dag`                   varchar(9)  NOT NULL,
    `huur_start`                 varchar(5)  NOT NULL,
    `huur_einde`                 varchar(5)  NOT NULL,
    `huur_kast_nr`               varchar(1)  NOT NULL,
    `kosten_ruimte`              varchar(3)  NOT NULL,
    `kosten_kast`                varchar(3)  NOT NULL,
    `kosten_totaal`              varchar(3)  NOT NULL,
    `kosten_borg`                varchar(3)  NOT NULL,
    `contract_ingangsdatum`      varchar(10) NOT NULL,
    `contract_einddatum`         varchar(10) NOT NULL,
    `contract_datum`             varchar(10) NOT NULL,
    `CreationDate`               timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate`           timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `band_contracts`
    CHANGE COLUMN `beuk_vertegenwoordiger` `beuk_vertegenwoordiger`         VARCHAR(50) NULL,
    CHANGE COLUMN `bandleider_naam` `bandleider_naam`                       VARCHAR(50) NULL,
    CHANGE COLUMN `bandleider_adres` `bandleider_adres`                     VARCHAR(50) NULL,
    CHANGE COLUMN `bandleider_postcode` `bandleider_postcode`               VARCHAR(6)  NULL,
    CHANGE COLUMN `bandleider_woonplaats` `bandleider_woonplaats`           VARCHAR(30) NULL,
    CHANGE COLUMN `bandleider_geboortedatum` `bandleider_geboortedatum`     VARCHAR(10) NULL,
    CHANGE COLUMN `bandleider_telefoonnummer1` `bandleider_telefoonnummer1` VARCHAR(10) NULL,
    CHANGE COLUMN `bandleider_telefoonnummer2` `bandleider_telefoonnummer2` VARCHAR(10) NULL,
    CHANGE COLUMN `bandleider_email` `bandleider_email`                     VARCHAR(30) NULL,
    CHANGE COLUMN `bandleider_bsn` `bandleider_bsn`                         VARCHAR(9)  NULL,
    CHANGE COLUMN `huur_oefenruimte_nr` `huur_oefenruimte_nr`               VARCHAR(1)  NULL,
    CHANGE COLUMN `huur_dag` `huur_dag`                                     VARCHAR(9)  NULL,
    CHANGE COLUMN `huur_start` `huur_start`                                 VARCHAR(5)  NULL,
    CHANGE COLUMN `huur_einde` `huur_einde`                                 VARCHAR(5)  NULL,
    CHANGE COLUMN `huur_kast_nr` `huur_kast_nr`                             VARCHAR(1)  NULL,
    CHANGE COLUMN `kosten_ruimte` `kosten_ruimte`                           VARCHAR(3)  NULL,
    CHANGE COLUMN `kosten_kast` `kosten_kast`                               VARCHAR(3)  NULL,
    CHANGE COLUMN `kosten_totaal` `kosten_totaal`                           VARCHAR(3)  NULL,
    CHANGE COLUMN `kosten_borg` `kosten_borg`                               VARCHAR(3)  NULL,
    CHANGE COLUMN `contract_ingangsdatum` `contract_ingangsdatum`           VARCHAR(10) NULL,
    CHANGE COLUMN `contract_einddatum` `contract_einddatum`                 VARCHAR(10) NULL,
    CHANGE COLUMN `contract_datum` `contract_datum`                         VARCHAR(10) NULL;

ALTER TABLE band_contracts
    ADD bandcode varchar(2) DEFAULT NULL AFTER id;

RENAME TABLE band_contracts TO contracts;