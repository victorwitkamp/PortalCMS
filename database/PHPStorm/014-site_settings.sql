create table site_settings
(
    setting          varchar(32)                         null,
    string_value     varchar(64)                         null,
    ModificationDate timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

