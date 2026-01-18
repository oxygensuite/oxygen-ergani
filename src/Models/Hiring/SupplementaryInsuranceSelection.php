<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring;

use OxygenSuite\OxygenErgani\Factories\Hiring\SupplementaryInsuranceSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Supplementary insurance selection for hiring declarations.
 *
 * Used in E3N, E3M, and E3PD schemas within the EpikourikiSelections element.
 *
 * @method static SupplementaryInsuranceSelectionFactory factory(int $count = 1)
 */
class SupplementaryInsuranceSelection extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_kod_epikourikis',
    ];

    public function getSupplementaryInsuranceCode(): ?string
    {
        return $this->get('f_kod_epikourikis');
    }

    public function setSupplementaryInsuranceCode(string $code): static
    {
        return $this->set('f_kod_epikourikis', $code);
    }
}
