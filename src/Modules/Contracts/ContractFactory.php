<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Modules\Contracts;

use PortalCMS\Core\Activity\Activity;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Session;
use PortalCMS\Modules\Invoices\InvoiceMapper;

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */
class ContractFactory
{
    public static function getById(int $id) : Contract
    {
        $mappedContract = ContractMapper::getById($id);
        $contractContact = new ContractContact(
            $mappedContract->bandleider_naam,
            $mappedContract->bandleider_adres,
            $mappedContract->bandleider_postcode,
            $mappedContract->bandleider_woonplaats,
            $mappedContract->bandleider_geboortedatum,
            $mappedContract->bandleider_telefoonnummer1,
            $mappedContract->bandleider_telefoonnummer2,
            $mappedContract->bandleider_email,
            (int) $mappedContract->bandleider_bsn
        );
        return new Contract(
            $mappedContract->id,
            $mappedContract->beuk_vertegenwoordiger,
            $mappedContract->band_naam,
            $mappedContract->bandcode,
            $contractContact,
            (int) $mappedContract->huur_oefenruimte_nr,
            $mappedContract->huur_dag,
            $mappedContract->huur_start,
            $mappedContract->huur_einde,
            (int) $mappedContract->huur_kast_nr,
            (int) $mappedContract->kosten_ruimte,
            (int) $mappedContract->kosten_kast,
            (int) $mappedContract->kosten_totaal,
            (int) $mappedContract->kosten_borg,
            $mappedContract->contract_ingangsdatum,
            $mappedContract->contract_einddatum,
            $mappedContract->contract_datum
        );
    }

    public static function new(): bool
    {
        $kosten_ruimte = (int) Request::post('kosten_ruimte', true);
        $kosten_kast = (int) Request::post('kosten_kast', true);
        $kosten_totaal = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact((string)Request::post('bandleider_naam', true), (string)Request::post('bandleider_adres', true), (string)Request::post('bandleider_postcode', true), (string)Request::post('bandleider_woonplaats', true), (string)Request::post('bandleider_geboortedatum', true), (string)Request::post('bandleider_telefoonnummer1', true), (string)Request::post('bandleider_telefoonnummer2', true), (string)Request::post('bandleider_email', true), (int)Request::post('bandleider_bsn', true));
        $contract = new Contract(null, (string)Request::post('beuk_vertegenwoordiger', true), (string)Request::post('band_naam', true), (string)Request::post('bandcode', true), $contractContact, (int)Request::post('huur_oefenruimte_nr', true), (string)Request::post('huur_dag', true), (string)Request::post('huur_start', true), (string)Request::post('huur_einde', true), (int)Request::post('huur_kast_nr', true), $kosten_ruimte, $kosten_kast, $kosten_totaal, (int)Request::post('kosten_borg', true), (string)Request::post('contract_ingangsdatum', true), (string)Request::post('contract_einddatumm', true), (string)Request::post('contract_datum', true));
        if (ContractMapper::new($contract)) {
            Activity::add('NewContract', Session::get('user_id'), 'ID: ' . ContractMapper::lastInsertedId());
            Session::add('feedback_positive', 'Contract toegevoegd.');
            return true;
        }
        Session::add('feedback_negative', 'Toevoegen van contract mislukt.');
        return false;
    }

    public static function update(): bool
    {
        $kosten_ruimte = (int) Request::post('kosten_ruimte', true);
        $kosten_kast = (int) Request::post('kosten_kast', true);
        $kosten_totaal = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact((string)Request::post('bandleider_naam', true), (string)Request::post('bandleider_adres', true), (string)Request::post('bandleider_postcode', true), (string)Request::post('bandleider_woonplaats', true), (string)Request::post('bandleider_geboortedatum', true), (string)Request::post('bandleider_telefoonnummer1', true), (string)Request::post('bandleider_telefoonnummer2', true), (string)Request::post('bandleider_email', true), (int)Request::post('bandleider_bsn', true));
        $contract = new Contract((int)Request::post('id', true), (string)Request::post('beuk_vertegenwoordiger', true), (string)Request::post('band_naam', true), (string)Request::post('bandcode', true), $contractContact, (int)Request::post('huur_oefenruimte_nr', true), (string)Request::post('huur_dag', true), (string)Request::post('huur_start', true), (string)Request::post('huur_einde', true), (int)Request::post('huur_kast_nr', true), $kosten_ruimte, $kosten_kast, $kosten_totaal, (int)Request::post('kosten_borg', true), (string)Request::post('contract_ingangsdatum', true), (string)Request::post('contract_einddatumm', true), (string)Request::post('contract_datum', true));
        if (empty(ContractMapper::getById((int)$contract->id))) {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt. Contract bestaat niet.');
        } elseif (ContractMapper::update($contract)) {
            Activity::add('UpdateContract', Session::get('user_id'), 'ID: ' . $contract->id);
            Session::add('feedback_positive', 'Contract gewijzigd.');
            return true;
        } else {
            Session::add('feedback_negative', 'Wijzigen van contract mislukt.');
        }
        return false;
    }

    public static function delete(int $id): bool
    {
        if (empty($id) || $id === null) {
            Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
        } else {
            $contract = ContractMapper::getById($id);
            if (empty($contract)) {
                Session::add('feedback_negative', 'Verwijderen van contract mislukt. Contract bestaat niet.');
            } elseif (!empty(InvoiceMapper::getByContractId($contract->id))) {
                Session::add('feedback_negative', 'Dit contract heeft al facturen.');
            } elseif (ContractMapper::delete($contract->id)) {
                Activity::add('DeleteContract', Session::get('user_id'), 'ID: ' . $contract->id);
                Session::add('feedback_positive', 'Contract verwijderd.');
                return true;
            } else {
                Session::add('feedback_negative', 'Verwijderen van contract mislukt.');
            }
        }
        return false;
    }
}
