/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS site_settings
(
    `setting`          VARCHAR(32),
    `string_value`     VARCHAR(64),
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_name', 'De Beuk Portal');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_description', 'Je bent zelf een beschrijving');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_url', 'https://portal.beukonline.nl');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_logo', '/content/img/placeholder-200x200.png');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_theme', 'darkly');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_layout', 'left-sidebar');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('WidgetComingEvents', '1');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('site_description_type', '1');
INSERT INTO `site_settings` (`setting`, `string_value`)
VALUES ('WidgetDebug', '1');