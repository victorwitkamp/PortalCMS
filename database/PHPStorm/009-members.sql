/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table members
(
    id                 int auto_increment
        primary key,
    jaarlidmaatschap   int(10)                             null,
    voorletters        varchar(30)                         null,
    voornaam           varchar(30)                         null,
    achternaam         varchar(30)                         null,
    geboortedatum      varchar(10)                         null,
    adres              varchar(50)                         null,
    postcode           varchar(6)                          null,
    huisnummer         varchar(6)                          null,
    woonplaats         varchar(30)                         null,
    telefoon_vast      varchar(10)                         null,
    telefoon_mobiel    varchar(10)                         null,
    emailadres         varchar(254)                        null,
    ingangsdatum       varchar(10)                         null,
    geslacht           varchar(50)                         null,
    nieuwsbrief        tinyint                             null,
    vrijwilliger       tinyint                             null,
    vrijwilligeroptie1 tinyint                             null,
    vrijwilligeroptie2 tinyint                             null,
    vrijwilligeroptie3 tinyint                             null,
    vrijwilligeroptie4 tinyint                             null,
    vrijwilligeroptie5 tinyint                             null,
    betalingswijze     varchar(30)                         null,
    iban               varchar(30)                         null,
    machtigingskenmerk varchar(30)                         null,
    status             int(2)    default 0                 null,
    opmerking          varchar(30)                         null,
    CreationDate       timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate   timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

