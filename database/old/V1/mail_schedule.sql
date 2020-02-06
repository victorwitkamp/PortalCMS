/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS mail_schedule
(
    id               INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    recipient_email  varchar(254),
    subject          varchar(255),
    body             TEXT,
    status           INT       NOT NULL DEFAULT 1,
    errormessage     TEXT               DEFAULT NULL,
    DateSent         timestamp NULL     DEFAULT NULL,
    CreationDate     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
)
