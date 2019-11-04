create table mail_recipient_types
(
	id int auto_increment
		primary key,
	name varchar(64) null,
	CreationDate timestamp default CURRENT_TIMESTAMP not null,
	ModificationDate timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

