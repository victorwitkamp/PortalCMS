<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Factory;

use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Contracts\Input\ContractInput;

final class ContractFactory
{
    public function create(ContractInput $input): Contract
    {
        $contract = new Contract();
        $this->update($contract, $input);

        return $contract;
    }

    public function update(Contract $contract, ContractInput $input): void
    {
        $contract->band_naam = $input->band_naam;
        $contract->bandcode = $input->bandcode;
        $contract->beuk_vertegenwoordiger = $input->beuk_vertegenwoordiger;
        $contract->bandleider_naam = $input->bandleider_naam;
        $contract->bandleider_adres = $input->bandleider_adres;
        $contract->bandleider_postcode = $input->bandleider_postcode;
        $contract->bandleider_woonplaats = $input->bandleider_woonplaats;
        $contract->bandleider_geboortedatum = $input->bandleider_geboortedatum;
        $contract->bandleider_telefoonnummer1 = $input->bandleider_telefoonnummer1;
        $contract->bandleider_telefoonnummer2 = $input->bandleider_telefoonnummer2;
        $contract->bandleider_email = $input->bandleider_email;
        $contract->bandleider_bsn = $input->bandleider_bsn;
        $contract->huur_oefenruimte_nr = $input->huur_oefenruimte_nr;
        $contract->huur_dag = $input->huur_dag;
        $contract->huur_start = $input->huur_start;
        $contract->huur_einde = $input->huur_einde;
        $contract->huur_kast_nr = $input->huur_kast_nr;
        $contract->kosten_ruimte = $input->kosten_ruimte;
        $contract->kosten_kast = $input->kosten_kast;
        $contract->kosten_totaal = (string) (
            (float) ($input->kosten_ruimte ?? 0)
            + (float) ($input->kosten_kast ?? 0)
        );
        $contract->kosten_borg = $input->kosten_borg;
        $contract->contract_ingangsdatum = $input->contract_ingangsdatum;
        $contract->contract_einddatum = $input->contract_einddatum;
        $contract->contract_datum = $input->contract_datum;
    }
}
