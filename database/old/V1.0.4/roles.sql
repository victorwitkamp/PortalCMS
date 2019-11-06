CREATE TABLE IF NOT EXISTS roles
(
    role_id   INTEGER     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    type      VARCHAR(10) NOT NULL DEFAULT 'CUSTOM'
);

INSERT INTO `roles` (`role_id`, `role_name`)
VALUES ('1', 'User');
INSERT INTO `roles` (`role_id`, `role_name`)
VALUES ('2', 'Administrator');
