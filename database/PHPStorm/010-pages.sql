create table pages
(
    id               varchar(32)                         null,
    name             varchar(32)                         null,
    content          text                                null,
    ModificationDate timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

