/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_templates
    ADD CreatedBy INT DEFAULT NULL AFTER status;
