create table events
(
    id               int auto_increment
        primary key,
    CreatedBy        int                                 not null,
    title            varchar(255)                        not null,
    start_event      datetime                            not null,
    end_event        datetime                            not null,
    description      text                                null,
    status           int       default 0                 null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

