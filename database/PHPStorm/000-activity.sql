create table activity
(
    id           int auto_increment primary key,
    CreationDate timestamp default CURRENT_TIMESTAMP not null,
    user_id      int                                 null,
    user_name    varchar(64)                         null,
    ip_address   varchar(15)                         null,
    activity     varchar(32)                         not null,
    details      varchar(32)                         null
);

