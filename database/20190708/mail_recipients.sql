/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS mail_recipients
(
    id               INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name             VARCHAR(64)           DEFAULT NULL,
    email            VARCHAR(254) NOT NULL,
    type             INT(1)       NOT NULL DEFAULT 1,
    mail_id          INT                   DEFAULT NULL,
    CreationDate     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
