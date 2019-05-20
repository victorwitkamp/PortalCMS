CREATE DATABASE
  membersdb
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci;

USE membersdb;

CREATE TABLE IF NOT EXISTS events (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` INT NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_event` datetime NOT NULL,
  `end_event` datetime NOT NULL,
  `description` TEXT,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mail_schedule (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  recipient_email varchar(254),
  subject varchar(255),
  body TEXT,
  status INT NOT NULL DEFAULT 1,
  errormessage TEXT DEFAULT NULL,
  DateSent timestamp  NULL DEFAULT NULL,
  CreationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModificationDate timestamp  NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mail_templates (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  type varchar(32),
  subject varchar(255),
  body TEXT,
  CreationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mail_text (
  `name` VARCHAR(32),
  `text` TEXT,
  `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `mail_text` (`name`, `text`) VALUES ('ResetPassword','Beste {USERNAME},<br><br>Open onderstaande link om je wachtwoord te resetten:<br><a href="{RESETLINK}">Reset wachtwoord</a><br><br>Met vriendelijke groet,<br><br>{SITENAME}');
INSERT INTO `mail_text` (`name`, `text`) VALUES ('Signup','Beste {USERNAME},<br><br>Klik op <a href="{ACTIVATELINK}">deze</a> link om uw account te activeren.<br>Indien de link niet werkt kunt u navigeren naar {ACTIVATEFORMLINK} en de volgende code invoeren: {CONFCODE}<br><br><br>Met vriendelijke groet,<br><br>{SITENAME}');

CREATE TABLE IF NOT EXISTS members (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `jaarlidmaatschap` INT(10),
  `voorletters` varchar(30),
  `voornaam` varchar(30),
  `achternaam` varchar(30),
  `geboortedatum` varchar(10),
  `adres` varchar(50),
  `postcode` varchar(6),
  `huisnummer` varchar(6),
  `woonplaats` varchar(30),
  `telefoon_vast` varchar(10),
  `telefoon_mobiel` varchar(10),
  `emailadres` varchar(254),
  `ingangsdatum` varchar(10),
  `geslacht` varchar(50),
  `nieuwsbrief` TINYINT,
  `vrijwilliger` TINYINT,
  `vrijwilligeroptie1` TINYINT,
  `vrijwilligeroptie2` TINYINT,
  `vrijwilligeroptie3` TINYINT,
  `vrijwilligeroptie4` TINYINT,
  `vrijwilligeroptie5` TINYINT,
  `betalingswijze` varchar(30),
  `iban` varchar(30) DEFAULT NULL,
  `machtigingskenmerk` varchar(30) DEFAULT NULL,
  `incasso_gelukt` varchar(30) DEFAULT NULL,
  `opmerking` varchar(30) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pages (
  `id` VARCHAR(32),
  `name` VARCHAR(32),
  `content` TEXT,
  `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `pages` (`id`, `name`, `content`) VALUES ('1','home','Dit is de homepage');

CREATE TABLE IF NOT EXISTS permissions (
  perm_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  perm_desc VARCHAR(50) NOT NULL
);

INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('1', 'settings-general');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('2', 'settings-users');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('3', 'events');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('4', 'membership');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('5', 'edit-page');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('6', 'band-contracts');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('7', 'profiles');

CREATE TABLE IF NOT EXISTS role_perm (
  role_id INTEGER NOT NULL,
  perm_id INTEGER NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(role_id),
  FOREIGN KEY (perm_id) REFERENCES permissions(perm_id)
);

INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '2');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '3');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '4');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '5');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '6');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '7');

CREATE TABLE IF NOT EXISTS roles (
  role_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  role_name VARCHAR(50) NOT NULL
);

INSERT INTO `roles` (`role_id`, `role_name`) VALUES ('1', 'Standard user');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES ('2', 'Administrator');

CREATE TABLE IF NOT EXISTS site_settings (
  `setting` VARCHAR(32),
  `string_value` VARCHAR(64),
  `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );

INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_name','De Beuk Portal');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_description','Je bent zelf een beschrijving');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_url','https://portal.victorwitkamp.nl');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_logo','/content/img/placeholder-200x200.png');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_theme','darkly');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_layout','left-sidebar');
INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('WidgetComingEvents','1');

CREATE TABLE IF NOT EXISTS user_role (
  user_id INTEGER NOT NULL,
  role_id INTEGER NOT NULL DEFAULT '1',
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES ('1', '2');

CREATE TABLE IF NOT EXISTS `users` (
 `user_id` int(11) NOT NULL AUTO_INCREMENT,
 `session_id` varchar(48) DEFAULT NULL,
 `user_name` varchar(64)  NOT NULL,
 `user_password_hash` varchar(255)  DEFAULT NULL,
 `user_email` varchar(254)  NOT NULL,
 `user_active` tinyint(1) NOT NULL DEFAULT '0',
 `user_deleted` tinyint(1) NOT NULL DEFAULT '0',
 `user_account_type` tinyint(1) NOT NULL DEFAULT '1',
 `user_has_avatar` tinyint(1) NOT NULL DEFAULT '0',
 `user_remember_me_token` varchar(64)  DEFAULT NULL,
 `user_creation_timestamp` bigint(20) DEFAULT NULL,
 `user_suspension_timestamp` bigint(20) DEFAULT NULL,
 `user_last_login_timestamp` bigint(20) DEFAULT NULL,
 `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0',
 `user_last_failed_login` int(10) DEFAULT NULL,
 `user_activation_hash` varchar(40)  DEFAULT NULL,
 `user_password_reset_hash` char(40)  DEFAULT NULL,
 `user_password_reset_timestamp` bigint(20) DEFAULT NULL,
 `user_provider_type` text,
 PRIMARY KEY (`user_id`),
 UNIQUE KEY `user_name` (`user_name`),
 UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3;

INSERT INTO `users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_active`, `user_deleted`, `user_account_type`, 
`user_has_avatar`, `user_remember_me_token`, `user_suspension_timestamp`, `user_last_login_timestamp`, 
`user_failed_logins`, `user_last_failed_login`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, 
`user_provider_type`, `user_fbid`, `CreationDate`, `ModificationDate`) 
VALUES (1, '6utnam8riah7q5a3c4spmb755g', 'UKqoZuJp', '$2y$10$cnaUCc2fooIJLdpxQFYMSuiYAU2ThT3AeuS1Nkku92FonoCIQeg7K', 'admin@admin.com', 
1, 0, 7, 0, '3075ac4a3b52ae8055a3afd942dc2f46dcdd31e7818b3202ff6c38378bdb394b', NULL, '2019-04-21 21:00:18', 0, '2019-04-16 18:53:43',
 NULL, '', '0000-00-00 00:00:00', 'DEFAULT', NULL, '2019-04-14 22:28:06', '2019-04-21 21:00:18');
