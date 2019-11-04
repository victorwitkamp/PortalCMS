create table role_perm
(
	role_id int not null,
	perm_id int not null,
	constraint role_perm_ibfk_1
		foreign key (role_id) references roles (role_id),
	constraint role_perm_ibfk_2
		foreign key (perm_id) references permissions (perm_id)
);

create index perm_id
	on role_perm (perm_id);

create index role_id
	on role_perm (role_id);

