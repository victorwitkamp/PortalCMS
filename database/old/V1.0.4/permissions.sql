CREATE TABLE IF NOT EXISTS permissions
(
    perm_id   INTEGER     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    perm_desc VARCHAR(50) NOT NULL
);

DELETE
FROM permissions;
INSERT INTO permissions (perm_id, perm_desc)
VALUES (1, 'site-settings');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (2, 'user-management');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (3, 'role-management');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (4, 'recent-activity');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (5, 'mail-scheduler');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (6, 'mail-templates');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (7, 'debug');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (8, 'rental-contracts');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (9, 'rental-invoices');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (10, 'rental-products');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (11, 'events');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (12, 'membership');
INSERT INTO permissions (perm_id, perm_desc)
VALUES (13, 'marketing-bar');