<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Invoices\InvoiceModel;

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */

class ContractModel
{
    public $vertegenwoordiger_beuk;
    public $naam;
    public $code;
    public $contactpersoon_naam;
    public $contactpersoon_adres;
    public $contactpersoon_woonplaats;
    public $contactpersoon_geboortedatum;
    public $contactpersoon_telefoonnummer1;
    public $contactpersoon_telefoonnummer2;
    public $contactpersoon_email;
    public $contactpersoon_bsn;

    public static function new()
    {
        $kosten_ruimte              = Request::post('kosten_ruimte', true);
        $kosten_kast                = Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        if (ContractMapper::new(
            Request::post('beuk_vertegenwoordiger', true),
            Request::post('band_naam', true),
            Request::post('bandcode', true),
            Request::post('bandleider_naam', true),
            Request::post('bandleider_adres', true),
            Request::post('bandleider_postcode', true),
            Request::post('bandleider_woonplaats', true),
            Request::post('bandleider_geboortedatum', true),
            Request::post('bandleider_telefoonnummer1', true),
            Request::post('bandleider_telefoonnummer2', true),
            Request::post('bandleider_email', true),
            Request::post('bandleider_bsn', true),
            Request::post('huur_oefenruimte_nr', true),
            Request::post('huur_dag', true),
            Request::post('huur_start', true),
            Request::post('huur_einde', true),
            Request::post('huur_kast_nr', true),
            $kosten_ruimte,
            $kosten_kast,
            $kosten_totaal,
            Request::post('kosten_borg', true),
            Request::post('contract_ingangsdatum', true),
            Request::post('contract_einddatumm', true),
            Request::post('contract_datum', true)
        )) {
            Session::add('feedback_positive', 'Contract toegevoegd.');
            Redirect::to('Contracts/');
        } else {
            Session::add('feedback_negative', 'Toevoegen van contract mislukt.');
            Redirect::to('Contracts/');
        }
    }

    public static function update()
    {
        $Id                         = (int) Request::post('id', true);
        $kosten_ruimte              = Request::post('kosten_ruimte', true);
        $kosten_kast                = Request::post('kosten_kast', true);
        $kosten_totaal              = $kosten_ruimte + $kosten_kast;
        if (!ContractMapper::exists($Id)) {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt.<br>Contract bestaat niet.');
            Redirect::to('Contracts/');
        }
        if (ContractMapper::update(
            $Id,
            Request::post('beuk_vertegenwoordiger', true),
            Request::post('band_naam', true),
            Request::post('bandcode', true),
            Request::post('bandleider_naam', true),
            Request::post('bandleider_adres', true),
            Request::post('bandleider_postcode', true),
            Request::post('bandleider_woonplaats', true),
            Request::post('bandleider_geboortedatum', true),
            Request::post('bandleider_telefoonnummer1', true),
            Request::post('bandleider_telefoonnummer2', true),
            Request::post('bandleider_email', true),
            Request::post('bandleider_bsn', true),
            Request::post('huur_oefenruimte_nr', true),
            Request::post('huur_dag', true),
            Request::post('huur_start', true),
            Request::post('huur_einde', true),
            Request::post('huur_kast_nr', true),
            $kosten_ruimte,
            $kosten_kast,
            $kosten_totaal,
            Request::post('kosten_borg', true),
            Request::post('contract_ingangsdatum', true),
            Request::post('contract_einddatumm', true),
            Request::post('contract_datum', true)
        )) {
            Session::add('feedback_positive', 'Contract gewijzigd.');
            Redirect::to('Contracts/');
        } else {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt.');
            Redirect::to('Contracts/');
        }
    }

    public static function delete(): bool
    {
        $contract_id = (int) Request::post('id', true);
        if (ContractMapper::exists($contract_id)) {
            if (empty(InvoiceModel::getByContractId($contract_id))) {
                if (ContractMapper::delete($contract_id)) {
                    Session::add('feedback_positive', 'Contract verwijderd.');
                    Redirect::to('Contracts');
                    return true;
                }
                Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
                Redirect::to('Contracts');
                return false;
            }
            Session::add('feedback_negative', 'Dit contract heeft al facturen.');
            Redirect::to('Contracts');
            return false;
        }
        Session::add('feedback_negative', 'Verwijderen van contract mislukt.<br>Contract bestaat niet.');
        Redirect::to('Contracts');
        return false;
    }
}
