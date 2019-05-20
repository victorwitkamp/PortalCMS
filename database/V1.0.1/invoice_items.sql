CREATE TABLE IF NOT EXISTS invoice_items (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  invoice_id INT NOT NULL,
  name varchar(50) NOT NULL,
  price INT NOT NULL,
  FOREIGN KEY (invoice_id) REFERENCES invoices(id),
  CreationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModificationDate timestamp  NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
)