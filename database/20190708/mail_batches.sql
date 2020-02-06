/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS mail_batches
(
    id               INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    status           BOOLEAN   NOT NULL DEFAULT false,
    CreationDate     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)