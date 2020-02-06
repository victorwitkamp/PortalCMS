/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table users
(
    user_id                       int auto_increment
        primary key,
    user_name                     varchar(64)                              not null,
    session_id                    varchar(48)                              null,
    user_password_hash            varchar(255)                             null,
    user_email                    varchar(254)                             not null,
    user_active                   tinyint(1) default 0                     not null,
    user_deleted                  tinyint(1) default 0                     not null,
    user_account_type             tinyint(1) default 1                     not null,
    user_has_avatar               tinyint(1) default 0                     not null,
    user_remember_me_token        varchar(64)                              null,
    user_suspension_timestamp     bigint                                   null,
    user_last_login_timestamp     timestamp  default CURRENT_TIMESTAMP     not null on update CURRENT_TIMESTAMP,
    user_failed_logins            tinyint(1) default 0                     not null,
    user_last_failed_login        timestamp  default '0000-00-00 00:00:00' not null,
    user_activation_hash          varchar(40)                              null,
    password_reset_hash           char(40)                                 null,
    user_password_reset_timestamp timestamp  default '0000-00-00 00:00:00' not null,
    user_provider_type            text                                     null,
    user_fbid                     varchar(100)                             null,
    CreationDate                  timestamp  default CURRENT_TIMESTAMP     not null,
    ModificationDate              timestamp  default CURRENT_TIMESTAMP     not null on update CURRENT_TIMESTAMP,
    constraint user_email
        unique (user_email),
    constraint user_name
        unique (user_name)
);

