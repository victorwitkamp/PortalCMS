/*
 * Copyright Victor Witkamp (c) 2020.
 */

CREATE TABLE IF NOT EXISTS user_role
(
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL DEFAULT '1',
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (role_id) REFERENCES roles (role_id)
);

INSERT INTO `user_role` (`user_id`, `role_id`)
VALUES ('1', '2');