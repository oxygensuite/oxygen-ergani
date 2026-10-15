<?php

namespace OxygenSuite\OxygenErgani\Models\ParentalLeave;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Child entry (AmkasE14) of an E14 parental leave declaration.
 *
 * Lists the child the leave is granted for, or a child of the same multiple birth.
 *
 * @see xsd/E14_v1.xsd
 */
class ParentalLeaveChild extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_amka',
        'f_xorhghsh_adeias',
        'f_polydymh_kyhsh',
    ];

    public function getAmka(): ?string
    {
        return $this->get('f_amka');
    }

    public function setAmka(string $amka): static
    {
        return $this->set('f_amka', $amka);
    }

    /**
     * Whether the leave is granted for this child.
     */
    public function getLeaveGranted(): ?string
    {
        return $this->get('f_xorhghsh_adeias');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setLeaveGranted(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_xorhghsh_adeias', $value);
    }

    /**
     * Whether the child is from a multiple birth.
     */
    public function getMultipleBirth(): ?string
    {
        return $this->get('f_polydymh_kyhsh');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setMultipleBirth(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_polydymh_kyhsh', $value);
    }
}
