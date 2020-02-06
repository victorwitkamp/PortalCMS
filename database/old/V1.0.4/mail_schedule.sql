/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE mail_schedule
    ADD attachment text default NULL after body;