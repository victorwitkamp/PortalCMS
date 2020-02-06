/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table invoices
(
    id               int auto_increment
        primary key,
    contract_id      int                                 null,
    year             int(4)                              null,
    month            int(2)                              null,
    factuurnummer    varchar(8)                          null,
    factuurdatum     date                                not null,
    vervaldatum      date                                null,
    status           int(2)    default 0                 null,
    mail_id          int                                 null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp                           null on update CURRENT_TIMESTAMP,
    constraint invoices_ibfk_1
        foreign key (contract_id) references contracts (id)
);

create index contract_id
    on invoices (contract_id);

