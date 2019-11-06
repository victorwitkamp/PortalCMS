create table mail_schedule
(
    id               int auto_increment
        primary key,
    batch_id         int                                 null,
    sender_email     varchar(254)                        null,
    recipient_email  varchar(254)                        null,
    subject          varchar(255)                        null,
    body             text                                null,
    attachment       text                                null,
    member_id        int                                 null,
    user_id          int                                 null,
    status           int       default 1                 not null,
    errormessage     text                                null,
    DateSent         timestamp                           null,
    CreatedBy        int                                 null,
    CreationDate     timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate timestamp                           null on update CURRENT_TIMESTAMP
);

