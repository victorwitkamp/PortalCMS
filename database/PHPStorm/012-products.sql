create table products
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	type int default 1 not null,
	price int not null,
	CreationDate timestamp default CURRENT_TIMESTAMP not null,
	ModificationDate timestamp null on update CURRENT_TIMESTAMP
);

