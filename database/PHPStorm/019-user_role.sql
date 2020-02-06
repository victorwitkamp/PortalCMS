/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table user_role
(
    user_id int           not null,
    role_id int default 1 not null,
    constraint user_role_ibfk_1
        foreign key (user_id) references users (user_id),
    constraint user_role_ibfk_2
        foreign key (role_id) references roles (role_id)
);

create index role_id
    on user_role (role_id);

create index user_id
    on user_role (user_id);

