CREATE TABLE IF NOT EXISTS role_perm (
  role_id INTEGER NOT NULL,
  perm_id INTEGER NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(role_id),
  FOREIGN KEY (perm_id) REFERENCES permissions(perm_id)
);

INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '2');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '3');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '4');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '5');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '6');
INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ('2', '7');