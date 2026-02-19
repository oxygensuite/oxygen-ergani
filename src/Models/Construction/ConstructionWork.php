<?php

namespace OxygenSuite\OxygenErgani\Models\Construction;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Construction work declaration model for E12_v1 schema.
 *
 * Container for daily construction site personnel declarations with nested employees.
 *
 * @see xsd/E12_v1.xsd
 */
class ConstructionWork extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_amoe',
        'f_rel_protocol',
        'f_rel_date',
        'f_date_from',
        'f_date_to',
        'f_phase',
        'f_ypiresia_sepe',
        'f_kallikratis_pararthmatos',
        'f_comments',
        'Ergazomenoi',
    ];

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getAmoe(): ?string
    {
        return $this->get('f_amoe');
    }

    public function setAmoe(string $amoe): static
    {
        return $this->set('f_amoe', $amoe);
    }

    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_rel_protocol');
    }

    public function setRelatedProtocol(string $protocol): static
    {
        return $this->set('f_rel_protocol', $protocol);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_rel_date');
    }

    public function setRelatedDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_rel_date', $date);
    }

    public function getDateFrom(): ?string
    {
        return $this->get('f_date_from');
    }

    public function setDateFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_from', $date);
    }

    public function getDateTo(): ?string
    {
        return $this->get('f_date_to');
    }

    public function setDateTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_to', $date);
    }

    public function getPhase(): ?string
    {
        return $this->get('f_phase');
    }

    public function setPhase(string $phase): static
    {
        return $this->set('f_phase', $phase);
    }

    public function getLaborInspectionCode(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    public function setLaborInspectionCode(string $code): static
    {
        return $this->set('f_ypiresia_sepe', $code);
    }

    public function getMunicipalityCode(): ?string
    {
        return $this->get('f_kallikratis_pararthmatos');
    }

    public function setMunicipalityCode(string $code): static
    {
        return $this->set('f_kallikratis_pararthmatos', $code);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * @return array<int, ConstructionEmployee>
     */
    public function getEmployees(): array
    {
        return $this->get('Ergazomenoi')['AmoeErgazomenosDate'] ?? [];
    }

    public function getEmployee(int $index): ?ConstructionEmployee
    {
        return $this->get('Ergazomenoi')['AmoeErgazomenosDate'][$index] ?? null;
    }

    /**
     * @param array<int, ConstructionEmployee> $employees
     */
    public function setEmployees(array $employees): static
    {
        return $this->set('Ergazomenoi', ['AmoeErgazomenosDate' => $employees]);
    }

    public function addEmployee(ConstructionEmployee $employee): static
    {
        $employees = $this->getEmployees();
        $employees[] = $employee;

        return $this->setEmployees($employees);
    }
}
