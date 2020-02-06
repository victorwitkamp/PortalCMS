/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_schedule
    ADD COLUMN sender_email varchar(254) AFTER id;
ALTER TABLE mail_schedule
    ADD COLUMN member_id INT AFTER body;
ALTER TABLE mail_schedule
    ADD COLUMN user_id INT AFTER member_id;
