<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Invoices\InvoiceMapper;

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */

class ContractModel
{
    public static function new()
    {
        $kosten_ruimte              = (int) Request::post('kosten_ruimte', true);
        $kosten_kast                = (int) Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact(
            (string) Request::post('bandleider_naam', true),
            (string) Request::post('bandleider_adres', true),
            (string) Request::post('bandleider_postcode', true),
            (string) Request::post('bandleider_woonplaats', true),
            (string) Request::post('bandleider_geboortedatum', true),
            (string) Request::post('bandleider_telefoonnummer1', true),
            (string) Request::post('bandleider_telefoonnummer2', true),
            (string) Request::post('bandleider_email', true),
            (int) Request::post('bandleider_bsn', true)
        );
        $contract = new Contract(
            null,
            (string) Request::post('beuk_vertegenwoordiger', true),
            (string) Request::post('band_naam', true),
            (string) Request::post('bandcode', true),
            $contractContact,
            (int) Request::post('huur_oefenruimte_nr', true),
            (string) Request::post('huur_dag', true),
            (string) Request::post('huur_start', true),
            (string) Request::post('huur_einde', true),
            (int) Request::post('huur_kast_nr', true),
            $kosten_ruimte,
            $kosten_kast,
            $kosten_totaal,
            (int) Request::post('kosten_borg', true),
            (string) Request::post('contract_ingangsdatum', true),
            (string) Request::post('contract_einddatumm', true),
            (string) Request::post('contract_datum', true)
        );
        if (ContractMapper::new($contract)) {
            Activity::add('NewContract', Session::get('user_id'), 'ID: ' . ContractMapper::lastInsertedId(), Session::get('user_name'));
            Session::add('feedback_positive', 'Contract toegevoegd.');
            Redirect::to('Contracts/');
        } else {
            Session::add('feedback_negative', 'Toevoegen van contract mislukt.');
            Redirect::to('Contracts/');
        }
    }

    public static function update()
    {
        $kosten_ruimte              = (int) Request::post('kosten_ruimte', true);
        $kosten_kast                = (int) Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact(
            (string) Request::post('bandleider_naam', true),
            (string) Request::post('bandleider_adres', true),
            (string) Request::post('bandleider_postcode', true),
            (string) Request::post('bandleider_woonplaats', true),
            (string) Request::post('bandleider_geboortedatum', true),
            (string) Request::post('bandleider_telefoonnummer1', true),
            (string) Request::post('bandleider_telefoonnummer2', true),
            (string) Request::post('bandleider_email', true),
            (int) Request::post('bandleider_bsn', true)
        );
        $contract = new Contract(
            (int) Request::post('id', true),
            (string) Request::post('beuk_vertegenwoordiger', true),
            (string) Request::post('band_naam', true),
            (string) Request::post('bandcode', true),
            $contractContact,
            (int) Request::post('huur_oefenruimte_nr', true),
            (string) Request::post('huur_dag', true),
            (string) Request::post('huur_start', true),
            (string) Request::post('huur_einde', true),
            (int) Request::post('huur_kast_nr', true),
            $kosten_ruimte,
            $kosten_kast,
            $kosten_totaal,
            (int) Request::post('kosten_borg', true),
            (string) Request::post('contract_ingangsdatum', true),
            (string) Request::post('contract_einddatumm', true),
            (string) Request::post('contract_datum', true)
        );
        if (empty(ContractMapper::getById($contract->id))) {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt. Contract bestaat niet.');
            Redirect::to('Contracts/');
        }
        if (ContractMapper::update($contract)) {
            Activity::add('UpdateContract', Session::get('user_id'), 'ID: ' . $contract->id, Session::get('user_name'));
            Session::add('feedback_positive', 'Contract gewijzigd.');
            Redirect::to('Contracts/');
        } else {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt.');
            Redirect::to('Contracts/');
        }
    }

    public static function delete(): bool
    {
        $contractId = (int) Request::post('id', true);
        if (empty(ContractMapper::getById($contractId))) {
            Session::add('feedback_negative', 'Verwijderen van contract mislukt. Contract bestaat niet.');
        } elseif (!empty(InvoiceMapper::getByContractId($contractId))) {
            Session::add('feedback_negative', 'Dit contract heeft al facturen.');
        } elseif (ContractMapper::delete($contractId)) {
            Activity::add('DeleteContract', Session::get('user_id'), 'ID: ' . $contractId, Session::get('user_name'));
            Session::add('feedback_positive', 'Contract verwijderd.');
            Redirect::to('Contracts/Index');
            return true;
        } else {
            Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
        }
        Redirect::to('Contracts/Index');
        return false;
    }
}
