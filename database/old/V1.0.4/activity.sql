DROP table user_activity;


CREATE TABLE
    IF NOT EXISTS activity
(
    id           INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CreationDate timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id      INT(11),
    user_name    VARCHAR(64),
    ip_address   VARCHAR(15),
    activity     VARCHAR(32) NOT NULL,
    details      VARCHAR(32)
);