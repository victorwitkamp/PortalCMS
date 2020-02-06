/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS pages
(
    `id`               VARCHAR(32),
    `name`             VARCHAR(32),
    `content`          TEXT,
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `pages` (`id`, `name`, `content`)
VALUES ('1', 'home', 'Dit is de homepage');
