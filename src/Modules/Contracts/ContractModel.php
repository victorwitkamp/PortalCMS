<?php


declare(strict_types=1);

namespace App\Modules\Contracts;

use App\Core\Activity\Activity;
use App\Core\Session\Session;
use App\Modules\Invoices\InvoiceMapper;

/**
 * Class : Contract (Contract.php)
 * Details : Class for the contracts of bands who rent a practice room
 */
class ContractModel
{
    public function new(): bool
    {
        $kosten_ruimte = (int)$this->request->get('kosten_ruimte');
        $kosten_kast = (int)$this->request->get('kosten_kast');
        $kosten_totaal = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact((string)$this->request->get('bandleider_naam'), (string)$this->request->get('bandleider_adres'), (string)$this->request->get('bandleider_postcode'), (string)$this->request->get('bandleider_woonplaats'), (string)$this->request->get('bandleider_geboortedatum'), (string)$this->request->get('bandleider_telefoonnummer1'), (string)$this->request->get('bandleider_telefoonnummer2'), (string)$this->request->get('bandleider_email'), (int)$this->request->get('bandleider_bsn'));
        $contract = new Contract(null, (string)$this->request->get('beuk_vertegenwoordiger'), (string)$this->request->get('band_naam'), (string)$this->request->get('bandcode'), $contractContact, (int)$this->request->get('huur_oefenruimte_nr'), (string)$this->request->get('huur_dag'), (string)$this->request->get('huur_start'), (string)$this->request->get('huur_einde'), (int)$this->request->get('huur_kast_nr'), $kosten_ruimte, $kosten_kast, $kosten_totaal, (int)$this->request->get('kosten_borg'), (string)$this->request->get('contract_ingangsdatum'), (string)$this->request->get('contract_einddatumm'), (string)$this->request->get('contract_datum'));
        if (ContractMapper::new($contract)) {
            Activity::add('NewContract', Session::get('user_id'), 'ID: ' . ContractMapper::lastInsertedId());
            $this->addFlash('success','Contract toegevoegd.');
            return true;
        }
        $this->addFlash('danger','Toevoegen van contract mislukt.');
        return false;
    }

    public function update(): bool
    {
        $kosten_ruimte = (int)$this->request->get('kosten_ruimte');
        $kosten_kast = (int)$this->request->get('kosten_kast');
        $kosten_totaal = $kosten_ruimte + $kosten_kast;

        $contractContact = new ContractContact((string)$this->request->get('bandleider_naam'), (string)$this->request->get('bandleider_adres'), (string)$this->request->get('bandleider_postcode'), (string)$this->request->get('bandleider_woonplaats'), (string)$this->request->get('bandleider_geboortedatum'), (string)$this->request->get('bandleider_telefoonnummer1'), (string)$this->request->get('bandleider_telefoonnummer2'), (string)$this->request->get('bandleider_email'), (int)$this->request->get('bandleider_bsn'));
        $contract = new Contract((int)$this->request->get('id'), (string)$this->request->get('beuk_vertegenwoordiger'), (string)$this->request->get('band_naam'), (string)$this->request->get('bandcode'), $contractContact, (int)$this->request->get('huur_oefenruimte_nr'), (string)$this->request->get('huur_dag'), (string)$this->request->get('huur_start'), (string)$this->request->get('huur_einde'), (int)$this->request->get('huur_kast_nr'), $kosten_ruimte, $kosten_kast, $kosten_totaal, (int)$this->request->get('kosten_borg'), (string)$this->request->get('contract_ingangsdatum'), (string)$this->request->get('contract_einddatumm'), (string)$this->request->get('contract_datum'));
        if (empty(ContractMapper::getById((int)$contract->id))) {
            $this->addFlash('danger','Wijzigen van contract mislukt. Contract bestaat niet.');
        } elseif (ContractMapper::update($contract)) {
            Activity::add('UpdateContract', Session::get('user_id'), 'ID: ' . $contract->id);
            $this->addFlash('success','Contract gewijzigd.');
            return true;
        } else {
            $this->addFlash('danger','Wijzigen van contract mislukt.');
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $contract = ContractMapper::getById($id);
        if (empty($contract)) {
            $this->addFlash('danger','Verwijderen van contract mislukt. Contract bestaat niet.');
        } elseif (!empty(InvoiceMapper::getByContractId($contract->id))) {
            $this->addFlash('danger','Dit contract heeft al facturen.');
        } elseif (ContractMapper::delete($contract->id)) {
            Activity::add('DeleteContract', Session::get('user_id'), 'ID: ' . $contract->id);
            $this->addFlash('success','Contract verwijderd.');
            return true;
        } else {
            $this->addFlash('danger','Verwijderen van contract mislukt.');
        }
        return false;
    }
}
