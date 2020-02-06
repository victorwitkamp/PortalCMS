/*
 * Copyright Victor Witkamp (c) 2020.
 */

ALTER TABLE users
    DROP COLUMN user_creation_timestamp;
ALTER TABLE users
    ADD CreationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users
    ADD ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE users
    DROP COLUMN user_last_login_timestamp;
ALTER TABLE users
    ADD user_last_login_timestamp timestamp NOT NULL AFTER user_suspension_timestamp;

ALTER TABLE users
    DROP COLUMN user_last_failed_login;
ALTER TABLE users
    ADD user_last_failed_login timestamp NOT NULL AFTER user_failed_logins;

ALTER TABLE users
    DROP COLUMN user_password_reset_timestamp;
ALTER TABLE users
    ADD user_password_reset_timestamp timestamp NOT NULL AFTER user_password_reset_hash;

ALTER TABLE users
    ADD user_fbid varchar(100) DEFAULT NULL AFTER user_provider_type;

ALTER TABLE users
    DROP session_id;
ALTER TABLE users
    ADD session_id varchar(48) DEFAULT NULL after user_name;
ALTER TABLE users
    CHANGE user_password_reset_hash password_reset_hash CHAR(40) DEFAULT NULL;

INSERT INTO `users` (`user_id`, `user_name`, `session_id`, `user_password_hash`, `user_email`, `user_active`,
                     `user_deleted`, `user_account_type`, `user_has_avatar`, `user_remember_me_token`,
                     `user_suspension_timestamp`, `user_last_login_timestamp`, `user_failed_logins`,
                     `user_last_failed_login`, `user_activation_hash`, `password_reset_hash`,
                     `user_password_reset_timestamp`, `user_provider_type`, `user_fbid`, `CreationDate`,
                     `ModificationDate`)
VALUES (1, 'admin', 'vn0rgrrve6h1eucigv4hinfu7k', '$2y$10$O.Rycurbyl9c4yMK2oCDOOZtk7KdnMib5TkvchCCHVCh8DNj15Qze',
        'victorwitkamp@gmail.com', 1, 0, 7, 0, 'd31a94031ed5279564041772f98047690f012688064e0eb9cae4562ae906a082', NULL,
        '2019-05-25 23:10:19', 3, '2019-05-25 23:10:36', NULL, '', '0000-00-00 00:00:00', 'DEFAULT',
        '10219802035908472', '2019-04-14 20:28:06', '2019-05-25 23:10:19'),
       (3, 'zoltan', '55cf5mo8m7n0m0e2ovnv3f9lhk', '$2y$12$Iz2TT/gV/WeWOJNz7LtoYu/JncgeLq.Zvm0gSjyey7C1i4T3sBbr6',
        'zoltan.ranti@gmail.com', 1, 0, 1, 0, NULL, NULL, '2019-05-24 02:44:52', 0, '2019-05-24 02:44:52', NULL, NULL,
        '0000-00-00 00:00:00', 'DEFAULT', NULL, '2019-05-19 14:37:21', '2019-05-24 02:44:52');
