/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_attachments
    ADD template_id INT DEFAULT NULL after mail_id;