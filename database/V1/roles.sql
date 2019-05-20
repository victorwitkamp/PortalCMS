CREATE TABLE IF NOT EXISTS roles (
  role_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  role_name VARCHAR(50) NOT NULL
);

INSERT INTO `roles` (`role_id`, `role_name`) VALUES ('1', 'Standard user');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES ('2', 'Administrator');