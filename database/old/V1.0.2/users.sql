ALTER TABLE users DROP session_id;
ALTER TABLE users ADD session_id varchar(48) DEFAULT NULL after user_name;