/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_batches
    ADD CreatedBy INT DEFAULT NULL AFTER UsedTemplate;
