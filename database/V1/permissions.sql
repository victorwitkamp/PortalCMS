CREATE TABLE IF NOT EXISTS permissions (
  perm_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  perm_desc VARCHAR(50) NOT NULL
);

INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('1', 'settings-general');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('2', 'settings-users');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('3', 'events');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('4', 'membership');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('5', 'edit-page');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('6', 'band-contracts');
INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES ('7', 'profiles');
