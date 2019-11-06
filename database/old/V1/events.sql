CREATE TABLE IF NOT EXISTS events
(
    `id`               INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `CreatedBy`        INT          NOT NULL,
    `title`            varchar(255) NOT NULL,
    `start_event`      datetime     NOT NULL,
    `end_event`        datetime     NOT NULL,
    `description`      TEXT,
    `CreationDate`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ModificationDate` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);