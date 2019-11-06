CREATE TABLE IF NOT EXISTS mail_templates
(
    id               INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type             varchar(32),
    subject          varchar(255),
    body             TEXT,
    CreationDate     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
