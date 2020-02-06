/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table invoice_items
(
    id               int auto_increment
        primary key,
    invoice_id       int                                 not null,
    name             varchar(50)                         not null,
    price            int                                 not null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp                           null on update CURRENT_TIMESTAMP,
    constraint invoice_items_ibfk_1
        foreign key (invoice_id) references invoices (id)
);

create index invoice_id
    on invoice_items (invoice_id);

