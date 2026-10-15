<?php

namespace OxygenSuite\OxygenErgani\Models\Motorcycle;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Motorcycle use declaration model for E13_v1 schema.
 *
 * Employer declaration that employees use a moped or motorcycle while working.
 *
 * @see xsd/E13_v1.xsd
 */
class MotorcycleUseDeclaration extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected array $defaults = [
        'Mixanes' => ['MixanesE4' => []],
    ];

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_ypiresia_sepe',
        'f_ypiresia_oaed',
        'f_comments',
        'f_afm_proswpoy',
        'Mixanes',
    ];

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getLaborInspectionCode(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    /**
     * @param string $code Labour inspectorate service code (5 digits)
     */
    public function setLaborInspectionCode(string $code): static
    {
        return $this->set('f_ypiresia_sepe', $code);
    }

    public function getDypaServiceCode(): ?string
    {
        return $this->get('f_ypiresia_oaed');
    }

    /**
     * @param string $code DYPA service code (6 digits)
     */
    public function setDypaServiceCode(string $code): static
    {
        return $this->set('f_ypiresia_oaed', $code);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    public function getLegalRepresentativeAfm(): ?string
    {
        return $this->get('f_afm_proswpoy');
    }

    public function setLegalRepresentativeAfm(string $afm): static
    {
        return $this->set('f_afm_proswpoy', $afm);
    }

    /**
     * @return array<int, MotorcycleVehicle>
     */
    public function getVehicles(): array
    {
        return $this->get('Mixanes')['MixanesE4'] ?? [];
    }

    public function getVehicle(int $index): ?MotorcycleVehicle
    {
        return $this->getVehicles()[$index] ?? null;
    }

    /**
     * @param array<int, MotorcycleVehicle> $vehicles
     */
    public function setVehicles(array $vehicles): static
    {
        return $this->set('Mixanes', ['MixanesE4' => $vehicles]);
    }

    public function addVehicle(MotorcycleVehicle $vehicle): static
    {
        $vehicles = $this->getVehicles();
        $vehicles[] = $vehicle;

        return $this->setVehicles($vehicles);
    }
}
