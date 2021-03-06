/*
 * Copyright Victor Witkamp (c) 2020.
 */

create table contracts
(
    id                         int auto_increment primary key,
    bandcode                   varchar(2)                          null,
    beuk_vertegenwoordiger     varchar(50)                         null,
    band_naam                  varchar(50)                         not null,
    bandleider_naam            varchar(50)                         null,
    bandleider_adres           varchar(50)                         null,
    bandleider_postcode        varchar(6)                          null,
    bandleider_woonplaats      varchar(30)                         null,
    bandleider_geboortedatum   varchar(10)                         null,
    bandleider_telefoonnummer1 varchar(10)                         null,
    bandleider_telefoonnummer2 varchar(10)                         null,
    bandleider_email           varchar(30)                         null,
    bandleider_bsn             varchar(9)                          null,
    huur_oefenruimte_nr        varchar(1)                          null,
    huur_dag                   varchar(9)                          null,
    huur_start                 varchar(5)                          null,
    huur_einde                 varchar(5)                          null,
    huur_kast_nr               varchar(1)                          null,
    kosten_ruimte              varchar(3)                          null,
    kosten_kast                varchar(3)                          null,
    kosten_totaal              varchar(3)                          null,
    kosten_borg                varchar(3)                          null,
    contract_ingangsdatum      varchar(10)                         null,
    contract_einddatum         varchar(10)                         null,
    contract_datum             varchar(10)                         null,
    CreationDate               timestamp default CURRENT_TIMESTAMP not null,
    ModificationDate           timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP
);

