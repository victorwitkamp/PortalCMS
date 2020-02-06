/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table mail_batches
(
    id               int auto_increment
        primary key,
    status           tinyint(1) default 0                 not null,
    DateSent         timestamp                            null,
    UsedTemplate     int                                  null,
    CreatedBy        int                                  null,
    CreationDate     timestamp  default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp  default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

