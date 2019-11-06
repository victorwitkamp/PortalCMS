ALTER TABLE members
    DROP COLUMN incasso_gelukt;
ALTER TABLE members
    ADD status int(2) DEFAULT 0 after machtigingskenmerk;