<?php

declare(strict_types=1);

namespace PortalCMS\Features\Invoices\Input;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateInvoicesInput
{
    #[Assert\Range(min: 2000, max: 9999)]
    public int $year;

    #[Assert\Range(min: 1, max: 12)]
    public int $month;

    /** @var int[] */
    #[Assert\Count(min: 1)]
    #[Assert\All([ new Assert\Positive() ])]
    public array $contract_id;

    #[Assert\NotNull]
    public DateTimeImmutable $factuurdatum;

    /**
     * @param int[] $contract_id
     */
    public function __construct(
        int $year,
        int $month,
        array $contract_id,
        DateTimeImmutable $factuurdatum,
    ) {
        $this->year = $year;
        $this->month = $month;
        $this->contract_id = array_map('intval', $contract_id);
        $this->factuurdatum = $factuurdatum;
    }
}
