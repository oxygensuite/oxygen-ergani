<?php

namespace OxygenSuite\OxygenErgani\Models\Motorcycle;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\VehicleOwner;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Vehicle entry (MixanesE4) of an E13 motorcycle use declaration.
 *
 * A moped or motorcycle used by an employee while working.
 *
 * @see xsd/E13_v1.xsd
 */
class MotorcycleVehicle extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_licence_plate',
        'f_is_owner',
        'f_afm_ergazomenou',
        'f_eponimo_ergazomenou',
        'f_onoma_ergazomenou',
        'f_onoma_patera',
        'f_date_enarxis_xrisis',
        'f_hour_enarxis_xrisis',
        'f_date_lixis_xrisis',
        'f_paratiriseis',
    ];

    public function getLicencePlate(): ?string
    {
        return $this->get('f_licence_plate');
    }

    public function setLicencePlate(string $plate): static
    {
        return $this->set('f_licence_plate', $plate);
    }

    public function getOwner(): ?string
    {
        return $this->get('f_is_owner');
    }

    /**
     * @param VehicleOwner|string|int $owner 0=Employer, 1=Employee (or use VehicleOwner enum)
     */
    public function setOwner(VehicleOwner|string|int $owner): static
    {
        if ($owner instanceof VehicleOwner) {
            $owner = $owner->value;
        }

        return $this->set('f_is_owner', (string) $owner);
    }

    public function getAfm(): ?string
    {
        return $this->get('f_afm_ergazomenou');
    }

    public function setAfm(string $afm): static
    {
        return $this->set('f_afm_ergazomenou', $afm);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponimo_ergazomenou');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponimo_ergazomenou', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma_ergazomenou');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma_ergazomenou', $firstName);
    }

    public function getFatherName(): ?string
    {
        return $this->get('f_onoma_patera');
    }

    public function setFatherName(string $fatherName): static
    {
        return $this->set('f_onoma_patera', $fatherName);
    }

    public function getStartDate(): ?string
    {
        return $this->get('f_date_enarxis_xrisis');
    }

    public function setStartDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_enarxis_xrisis', $date);
    }

    public function getStartTime(): ?string
    {
        return $this->get('f_hour_enarxis_xrisis');
    }

    /**
     * @param string $time Time the vehicle use starts (HH:MM)
     */
    public function setStartTime(string $time): static
    {
        return $this->set('f_hour_enarxis_xrisis', $time);
    }

    public function getEndDate(): ?string
    {
        return $this->get('f_date_lixis_xrisis');
    }

    public function setEndDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_lixis_xrisis', $date);
    }

    public function getNotes(): ?string
    {
        return $this->get('f_paratiriseis');
    }

    public function setNotes(string $notes): static
    {
        return $this->set('f_paratiriseis', $notes);
    }
}
