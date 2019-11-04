create table mail_attachments
(
	id int auto_increment
		primary key,
	mail_id int null,
	template_id int null,
	path varchar(254) null,
	name varchar(255) null,
	extension varchar(255) null,
	encoding varchar(255) null,
	type varchar(255) null,
	CreationDate timestamp default CURRENT_TIMESTAMP not null,
	ModificationDate timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

