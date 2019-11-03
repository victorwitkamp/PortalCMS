ALTER TABLE mail_batches ADD DateSent timestamp NULL DEFAULT NULL AFTER status;
ALTER TABLE mail_batches ADD UsedTemplate int NULL DEFAULT NULL AFTER DateSent;