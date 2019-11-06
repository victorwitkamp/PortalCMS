ALTER TABLE mail_schedule
    ADD CreatedBy INT DEFAULT NULL AFTER DateSent;
