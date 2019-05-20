INSERT INTO `site_settings` (`setting`, `string_value`) VALUES ('site_description_type','1');

ALTER TABLE members DROP COLUMN incasso_gelukt;
ALTER TABLE members ADD status int(2) DEFAULT 0 after machtigingskenmerk;

TRUNCATE TABLE user_role;
TRUNCATE TABLE role_perm;
DELETE FROM roles;
DELETE FROM permissions;

INSERT INTO permissions (perm_id, perm_desc) VALUES (1,'site-settings');
INSERT INTO permissions (perm_id, perm_desc) VALUES (2,'user-management');
INSERT INTO permissions (perm_id, perm_desc) VALUES (3,'role-management');
INSERT INTO permissions (perm_id, perm_desc) VALUES (4,'recent-activity');
INSERT INTO permissions (perm_id, perm_desc) VALUES (5,'mail-scheduler');
INSERT INTO permissions (perm_id, perm_desc) VALUES (6,'mail-templates');
INSERT INTO permissions (perm_id, perm_desc) VALUES (7,'debug');

ALTER TABLE users DROP session_id;
ALTER TABLE users ADD session_id varchar(48) DEFAULT NULL after user_name;