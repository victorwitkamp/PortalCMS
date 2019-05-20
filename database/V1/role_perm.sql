CREATE TABLE IF NOT EXISTS role_perm (
  role_id INTEGER NOT NULL,
  perm_id INTEGER NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(role_id),
  FOREIGN KEY (perm_id) REFERENCES permissions(perm_id)
);
