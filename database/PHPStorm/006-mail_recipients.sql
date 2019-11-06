create table mail_recipients
(
    id               int auto_increment
        primary key,
    name             varchar(64)                         null,
    email            varchar(254)                        not null,
    type             int(1)    default 1                 not null,
    mail_id          int                                 null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

