/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS products
(
    id               INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name             varchar(50) NOT NULL,
    type             INT         NOT NULL DEFAULT 1,
    price            INT         NOT NULL,
    CreationDate     timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp   NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
)