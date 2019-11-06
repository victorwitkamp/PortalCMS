-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.26-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.5.0.5282
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table membersdb.activity
CREATE TABLE IF NOT EXISTS `activity`
(
    `id`           int(11)     NOT NULL AUTO_INCREMENT,
    `CreationDate` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_id`      int(11)              DEFAULT NULL,
    `user_name`    varchar(64)          DEFAULT NULL,
    `ip_address`   varchar(15)          DEFAULT NULL,
    `activity`     varchar(32) NOT NULL,
    `details`      varchar(32)          DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.contracts
CREATE TABLE IF NOT EXISTS `contracts`
(
    `id`                         int(11)     NOT NULL AUTO_INCREMENT,
    `bandcode`                   varchar(2)           DEFAULT NULL,
    `beuk_vertegenwoordiger`     varchar(50)          DEFAULT NULL,
    `band_naam`                  varchar(50) NOT NULL,
    `bandleider_naam`            varchar(50)          DEFAULT NULL,
    `bandleider_adres`           varchar(50)          DEFAULT NULL,
    `bandleider_postcode`        varchar(6)           DEFAULT NULL,
    `bandleider_woonplaats`      varchar(30)          DEFAULT NULL,
    `bandleider_geboortedatum`   varchar(10)          DEFAULT NULL,
    `bandleider_telefoonnummer1` varchar(10)          DEFAULT NULL,
    `bandleider_telefoonnummer2` varchar(10)          DEFAULT NULL,
    `bandleider_email`           varchar(30)          DEFAULT NULL,
    `bandleider_bsn`             varchar(9)           DEFAULT NULL,
    `huur_oefenruimte_nr`        varchar(1)           DEFAULT NULL,
    `huur_dag`                   varchar(9)           DEFAULT NULL,
    `huur_start`                 varchar(5)           DEFAULT NULL,
    `huur_einde`                 varchar(5)           DEFAULT NULL,
    `huur_kast_nr`               varchar(1)           DEFAULT NULL,
    `kosten_ruimte`              varchar(3)           DEFAULT NULL,
    `kosten_kast`                varchar(3)           DEFAULT NULL,
    `kosten_totaal`              varchar(3)           DEFAULT NULL,
    `kosten_borg`                varchar(3)           DEFAULT NULL,
    `contract_ingangsdatum`      varchar(10)          DEFAULT NULL,
    `contract_einddatum`         varchar(10)          DEFAULT NULL,
    `contract_datum`             varchar(10)          DEFAULT NULL,
    `CreationDate`               timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate`           timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 10
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.events
CREATE TABLE IF NOT EXISTS `events`
(
    `id`               int(11)      NOT NULL AUTO_INCREMENT,
    `CreatedBy`        int(11)      NOT NULL,
    `title`            varchar(255) NOT NULL,
    `start_event`      datetime     NOT NULL,
    `end_event`        datetime     NOT NULL,
    `description`      text,
    `status`           int(11)               DEFAULT '0',
    `CreationDate`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 17
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.invoices
CREATE TABLE IF NOT EXISTS `invoices`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `contract_id`      int(11)            DEFAULT NULL,
    `year`             int(4)             DEFAULT NULL,
    `month`            int(2)             DEFAULT NULL,
    `factuurnummer`    varchar(8)         DEFAULT NULL,
    `factuurdatum`     date      NOT NULL,
    `vervaldatum`      date               DEFAULT NULL,
    `status`           int(2)             DEFAULT '0',
    `mail_id`          int(11)            DEFAULT NULL,
    `CreationDate`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `contract_id` (`contract_id`),
    CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 94
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.invoice_items
CREATE TABLE IF NOT EXISTS `invoice_items`
(
    `id`               int(11)     NOT NULL AUTO_INCREMENT,
    `invoice_id`       int(11)     NOT NULL,
    `name`             varchar(50) NOT NULL,
    `price`            int(11)     NOT NULL,
    `CreationDate`     timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp   NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`),
    CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 177
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.mail_attachments
CREATE TABLE IF NOT EXISTS `mail_attachments`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `mail_id`          int(11)            DEFAULT NULL,
    `template_id`      int(11)            DEFAULT NULL,
    `path`             varchar(254)       DEFAULT NULL,
    `name`             varchar(255)       DEFAULT NULL,
    `extension`        varchar(255)       DEFAULT NULL,
    `encoding`         varchar(255)       DEFAULT NULL,
    `type`             varchar(255)       DEFAULT NULL,
    `CreationDate`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 612
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.mail_batches
CREATE TABLE IF NOT EXISTS `mail_batches`
(
    `id`               int(11)    NOT NULL AUTO_INCREMENT,
    `status`           tinyint(1) NOT NULL DEFAULT '0',
    `DateSent`         timestamp  NULL     DEFAULT NULL,
    `UsedTemplate`     int(11)             DEFAULT NULL,
    `CreatedBy`        int(11)             DEFAULT NULL,
    `CreationDate`     timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp  NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 70
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.mail_recipients
CREATE TABLE IF NOT EXISTS `mail_recipients`
(
    `id`               int(11)      NOT NULL AUTO_INCREMENT,
    `name`             varchar(64)           DEFAULT NULL,
    `email`            varchar(254) NOT NULL,
    `type`             int(1)       NOT NULL DEFAULT '1',
    `mail_id`          int(11)               DEFAULT NULL,
    `CreationDate`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 250
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.mail_schedule
CREATE TABLE IF NOT EXISTS `mail_schedule`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `batch_id`         int(11)            DEFAULT NULL,
    `sender_email`     varchar(254)       DEFAULT NULL,
    `recipient_email`  varchar(254)       DEFAULT NULL,
    `subject`          varchar(255)       DEFAULT NULL,
    `body`             text,
    `attachment`       text,
    `member_id`        int(11)            DEFAULT NULL,
    `user_id`          int(11)            DEFAULT NULL,
    `status`           int(11)   NOT NULL DEFAULT '1',
    `errormessage`     text,
    `DateSent`         timestamp NULL     DEFAULT NULL,
    `CreatedBy`        int(11)            DEFAULT NULL,
    `CreationDate`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 147
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.mail_templates
CREATE TABLE IF NOT EXISTS `mail_templates`
(
    `id`               int(11)   NOT NULL AUTO_INCREMENT,
    `type`             varchar(32)        DEFAULT NULL,
    `name`             varchar(32)        DEFAULT NULL,
    `subject`          varchar(255)       DEFAULT NULL,
    `body`             text,
    `status`           int(11)   NOT NULL DEFAULT '1',
    `CreatedBy`        int(11)            DEFAULT NULL,
    `CreationDate`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 17
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.members
CREATE TABLE IF NOT EXISTS `members`
(
    `id`                 int(11)   NOT NULL AUTO_INCREMENT,
    `jaarlidmaatschap`   int(10)            DEFAULT NULL,
    `voorletters`        varchar(30)        DEFAULT NULL,
    `voornaam`           varchar(30)        DEFAULT NULL,
    `achternaam`         varchar(30)        DEFAULT NULL,
    `geboortedatum`      varchar(10)        DEFAULT NULL,
    `adres`              varchar(50)        DEFAULT NULL,
    `postcode`           varchar(6)         DEFAULT NULL,
    `huisnummer`         varchar(6)         DEFAULT NULL,
    `woonplaats`         varchar(30)        DEFAULT NULL,
    `telefoon_vast`      varchar(10)        DEFAULT NULL,
    `telefoon_mobiel`    varchar(10)        DEFAULT NULL,
    `emailadres`         varchar(254)       DEFAULT NULL,
    `ingangsdatum`       varchar(10)        DEFAULT NULL,
    `geslacht`           varchar(50)        DEFAULT NULL,
    `nieuwsbrief`        tinyint(4)         DEFAULT NULL,
    `vrijwilliger`       tinyint(4)         DEFAULT NULL,
    `vrijwilligeroptie1` tinyint(4)         DEFAULT NULL,
    `vrijwilligeroptie2` tinyint(4)         DEFAULT NULL,
    `vrijwilligeroptie3` tinyint(4)         DEFAULT NULL,
    `vrijwilligeroptie4` tinyint(4)         DEFAULT NULL,
    `vrijwilligeroptie5` tinyint(4)         DEFAULT NULL,
    `betalingswijze`     varchar(30)        DEFAULT NULL,
    `iban`               varchar(30)        DEFAULT NULL,
    `machtigingskenmerk` varchar(30)        DEFAULT NULL,
    `status`             int(2)             DEFAULT '0',
    `opmerking`          varchar(30)        DEFAULT NULL,
    `CreationDate`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate`   timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 51
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.pages
CREATE TABLE IF NOT EXISTS `pages`
(
    `id`               varchar(32)        DEFAULT NULL,
    `name`             varchar(32)        DEFAULT NULL,
    `content`          text,
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.permissions
CREATE TABLE IF NOT EXISTS `permissions`
(
    `perm_id`   int(11)     NOT NULL AUTO_INCREMENT,
    `perm_desc` varchar(50) NOT NULL,
    PRIMARY KEY (`perm_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 13
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.products
CREATE TABLE IF NOT EXISTS `products`
(
    `id`               int(11)     NOT NULL AUTO_INCREMENT,
    `name`             varchar(50) NOT NULL,
    `type`             int(11)     NOT NULL DEFAULT '1',
    `price`            int(11)     NOT NULL,
    `CreationDate`     timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp   NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.roles
CREATE TABLE IF NOT EXISTS `roles`
(
    `role_id`   int(11)     NOT NULL AUTO_INCREMENT,
    `role_name` varchar(50) NOT NULL,
    PRIMARY KEY (`role_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.role_perm
CREATE TABLE IF NOT EXISTS `role_perm`
(
    `role_id` int(11) NOT NULL,
    `perm_id` int(11) NOT NULL,
    KEY `role_id` (`role_id`),
    KEY `perm_id` (`perm_id`),
    CONSTRAINT `role_perm_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
    CONSTRAINT `role_perm_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`perm_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.site_settings
CREATE TABLE IF NOT EXISTS `site_settings`
(
    `setting`          varchar(32)        DEFAULT NULL,
    `string_value`     varchar(64)        DEFAULT NULL,
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.users
CREATE TABLE IF NOT EXISTS `users`
(
    `user_id`                       int(11)      NOT NULL AUTO_INCREMENT,
    `user_name`                     varchar(64)  NOT NULL,
    `session_id`                    varchar(48)           DEFAULT NULL,
    `user_password_hash`            varchar(255)          DEFAULT NULL,
    `user_email`                    varchar(254) NOT NULL,
    `user_active`                   tinyint(1)   NOT NULL DEFAULT '0',
    `user_deleted`                  tinyint(1)   NOT NULL DEFAULT '0',
    `user_account_type`             tinyint(1)   NOT NULL DEFAULT '1',
    `user_has_avatar`               tinyint(1)   NOT NULL DEFAULT '0',
    `user_remember_me_token`        varchar(64)           DEFAULT NULL,
    `user_suspension_timestamp`     bigint(20)            DEFAULT NULL,
    `user_last_login_timestamp`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `user_failed_logins`            tinyint(1)   NOT NULL DEFAULT '0',
    `user_last_failed_login`        timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `user_activation_hash`          varchar(40)           DEFAULT NULL,
    `password_reset_hash`           char(40)              DEFAULT NULL,
    `user_password_reset_timestamp` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `user_provider_type`            text,
    `user_fbid`                     varchar(100)          DEFAULT NULL,
    `CreationDate`                  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate`              timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `user_name` (`user_name`),
    UNIQUE KEY `user_email` (`user_email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
-- Dumping structure for table membersdb.user_role
CREATE TABLE IF NOT EXISTS `user_role`
(
    `user_id` int(11) NOT NULL,
    `role_id` int(11) NOT NULL DEFAULT '1',
    KEY `user_id` (`user_id`),
    KEY `role_id` (`role_id`),
    CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
    CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
