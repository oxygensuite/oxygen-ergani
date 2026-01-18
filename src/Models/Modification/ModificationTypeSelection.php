<?php

namespace OxygenSuite\OxygenErgani\Models\Modification;

use OxygenSuite\OxygenErgani\Factories\Modification\ModificationTypeSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Modification type selection model for TypesMetabolonMA.
 *
 * Represents a single modification type code for employment change declarations.
 *
 * @see xsd/MA_v1.xsd
 *
 * @method static ModificationTypeSelectionFactory factory(int $count = 1)
 */
class ModificationTypeSelection extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_typos_metabolhs',
    ];

    /**
     * Get the modification type code.
     */
    public function getModificationTypeCode(): ?string
    {
        return $this->get('f_typos_metabolhs');
    }

    /**
     * @param string $code Modification type code (max 10 chars)
     */
    public function setModificationTypeCode(string $code): static
    {
        return $this->set('f_typos_metabolhs', $code);
    }
}
