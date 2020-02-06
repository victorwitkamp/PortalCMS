/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_schedule
    ADD CreatedBy INT DEFAULT NULL AFTER DateSent;
