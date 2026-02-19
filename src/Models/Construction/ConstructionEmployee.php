<?php

namespace OxygenSuite\OxygenErgani\Models\Construction;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Construction work employee entry model for E12_v1 schema.
 *
 * Represents a single employee entry within a construction work declaration.
 *
 * @see xsd/E12_v1.xsd
 */
class ConstructionEmployee extends Model
{
    use HasFactory;
    /** @var array<string, string> */
    protected array $casts = [
        'f_apodoxes' => 'greek_float',
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm',
        'f_amka',
        'f_ama',
        'f_eponymo',
        'f_onoma',
        'f_onoma_patera',
        'f_date',
        'f_from',
        'f_to',
        'f_cancellation',
        'f_step',
        'f_ar_adeias',
        'f_hire_date',
        'f_apodoxes',
        'f_notes',
    ];

    public function getAfm(): ?string
    {
        return $this->get('f_afm');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm', $afm);
    }

    public function getAmka(): ?string
    {
        return $this->get('f_amka');
    }

    public function setAmka(string $amka): static
    {
        return $this->set('f_amka', $amka);
    }

    public function getAma(): ?string
    {
        return $this->get('f_ama');
    }

    public function setAma(string $ama): static
    {
        return $this->set('f_ama', $ama);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getFatherName(): ?string
    {
        return $this->get('f_onoma_patera');
    }

    public function setFatherName(string $fatherName): static
    {
        return $this->set('f_onoma_patera', $fatherName);
    }

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    public function setDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date', $date);
    }

    public function getTimeFrom(): ?string
    {
        return $this->get('f_from');
    }

    public function setTimeFrom(string $time): static
    {
        return $this->set('f_from', $time);
    }

    public function getTimeTo(): ?string
    {
        return $this->get('f_to');
    }

    public function setTimeTo(string $time): static
    {
        return $this->set('f_to', $time);
    }

    public function getCancellation(): ?string
    {
        return $this->get('f_cancellation');
    }

    public function setCancellation(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_cancellation', $value);
    }

    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_step');
    }

    public function setSpecialtyCode(string $code): static
    {
        return $this->set('f_step', $code);
    }

    public function getWorkPermitNumber(): ?string
    {
        return $this->get('f_ar_adeias');
    }

    public function setWorkPermitNumber(string $number): static
    {
        return $this->set('f_ar_adeias', $number);
    }

    public function getHireDate(): ?string
    {
        return $this->get('f_hire_date');
    }

    public function setHireDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_hire_date', $date);
    }

    public function getGrossDailyWage(): ?float
    {
        return $this->greekFloat('f_apodoxes');
    }

    public function setGrossDailyWage(float $amount): static
    {
        return $this->set('f_apodoxes', $amount);
    }

    public function getNotes(): ?string
    {
        return $this->get('f_notes');
    }

    public function setNotes(string $notes): static
    {
        return $this->set('f_notes', $notes);
    }
}
