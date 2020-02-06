/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table mail_templates
(
    id               int auto_increment
        primary key,
    type             varchar(32)                         null,
    name             varchar(32)                         null,
    subject          varchar(255)                        null,
    body             text                                null,
    status           int       default 1                 not null,
    CreatedBy        int                                 null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

