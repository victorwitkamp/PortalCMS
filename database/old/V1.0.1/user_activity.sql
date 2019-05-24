CREATE TABLE IF NOT EXISTS user_activity (
  activity_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  user_name VARCHAR(64) NOT NULL,
  ip_address VARCHAR(15),
  date_time INT(10) UNSIGNED NOT NULL,
  activity VARCHAR(32)
 );

ALTER TABLE user_activity ADD CreationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER date_time;
alter table user_activity ADD ModificationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER CreationDate;
alter table user_activity drop column date_time;