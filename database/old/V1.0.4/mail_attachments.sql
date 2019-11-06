CREATE TABLE IF NOT EXISTS mail_attachments
(
    id               INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    mail_id          INT                DEFAULT NULL,
    path             varchar(254)       DEFAULT NULL,
    name             varchar(255)       DEFAULT NULL,
    extension        varchar(255)       DEFAULT NULL,
    encoding         varchar(255)       DEFAULT NULL,
    type             varchar(255)       DEFAULT NULL,
    CreationDate     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)