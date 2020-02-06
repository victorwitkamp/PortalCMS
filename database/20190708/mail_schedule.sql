/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_schedule
    ADD batch_id INT DEFAULT NULL AFTER id
