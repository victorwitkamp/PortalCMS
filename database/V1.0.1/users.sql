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


INSERT INTO `users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_active`, `user_deleted`, `user_account_type`, 
`user_has_avatar`, `user_remember_me_token`, `user_suspension_timestamp`, `user_last_login_timestamp`, 
`user_failed_logins`, `user_last_failed_login`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, 
`user_provider_type`, `user_fbid`, `CreationDate`, `ModificationDate`) 
VALUES (1, '6utnam8riah7q5a3c4spmb755g', 'UKqoZuJp', '$2y$10$cnaUCc2fooIJLdpxQFYMSuiYAU2ThT3AeuS1Nkku92FonoCIQeg7K', 'admin@admin.com', 
1, 0, 7, 0, '3075ac4a3b52ae8055a3afd942dc2f46dcdd31e7818b3202ff6c38378bdb394b', NULL, '2019-04-21 21:00:18', 0, '2019-04-16 18:53:43',
 NULL, '', '0000-00-00 00:00:00', 'DEFAULT', NULL, '2019-04-14 22:28:06', '2019-04-21 21:00:18');
