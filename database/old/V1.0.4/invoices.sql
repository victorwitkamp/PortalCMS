CREATE TABLE IF NOT EXISTS invoices
(
    id               INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    contract_id      INTEGER,
    year             INT(4),
    month            INT(2),
    factuurnummer    varchar(8),
    factuurdatum     date      NOT NULL,
    vervaldatum      date      NOT NULL,
    status           int(2)             DEFAULT 0,
    FOREIGN KEY (contract_id) REFERENCES contracts (id),
    CreationDate     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ModificationDate timestamp NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);


ALTER TABLE invoices
    add mail_id int default NULL after status