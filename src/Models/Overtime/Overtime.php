<?php

namespace OxygenSuite\OxygenErgani\Models\Overtime;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

class Overtime extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_ypiresia_sepe',
        'f_ergodotikh_organwsh',
        'f_kad_kyria',
        'f_kad_deyt_1',
        'f_kad_deyt_2',
        'f_kad_deyt_3',
        'f_kad_deyt_4',
        'f_kad_pararthmatos',
        'f_kallikratis_pararthmatos',
        'f_comments',
        'f_afm_proswpoy',
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

    public function setRelatedDate(string $relatedDate): static
    {
        return $this->set('f_rel_date', $relatedDate);
    }

    public function getSepeService(): ?string
    {
        return $this->get('f_ypiresia_sepe');
    }

    public function setSepeService(string $sepeService): static
    {
        return $this->set('f_ypiresia_sepe', $sepeService);
    }

    public function getEmployerOrganization(): ?string
    {
        return $this->get('f_ergodotikh_organwsh');
    }

    public function setEmployerOrganization(string $organization): static
    {
        return $this->set('f_ergodotikh_organwsh', $organization);
    }

    public function getPrimaryKad(): ?string
    {
        return $this->get('f_kad_kyria');
    }

    public function setPrimaryKad(string $kad): static
    {
        return $this->set('f_kad_kyria', $kad);
    }

    public function getSecondaryKad1(): ?string
    {
        return $this->get('f_kad_deyt_1');
    }

    public function setSecondaryKad1(string $kad): static
    {
        return $this->set('f_kad_deyt_1', $kad);
    }

    public function getSecondaryKad2(): ?string
    {
        return $this->get('f_kad_deyt_2');
    }

    public function setSecondaryKad2(string $kad): static
    {
        return $this->set('f_kad_deyt_2', $kad);
    }

    public function getSecondaryKad3(): ?string
    {
        return $this->get('f_kad_deyt_3');
    }

    public function setSecondaryKad3(string $kad): static
    {
        return $this->set('f_kad_deyt_3', $kad);
    }

    public function getSecondaryKad4(): ?string
    {
        return $this->get('f_kad_deyt_4');
    }

    public function setSecondaryKad4(string $kad): static
    {
        return $this->set('f_kad_deyt_4', $kad);
    }

    public function getBranchKad(): ?string
    {
        return $this->get('f_kad_pararthmatos');
    }

    public function setBranchKad(string $kad): static
    {
        return $this->set('f_kad_pararthmatos', $kad);
    }

    public function getKallikratisCode(): ?string
    {
        return $this->get('f_kallikratis_pararthmatos');
    }

    public function setKallikratisCode(string $code): static
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

    public function getRepresentativeTin(): ?string
    {
        return $this->get('f_afm_proswpoy');
    }

    public function setRepresentativeTin(string $tin): static
    {
        return $this->set('f_afm_proswpoy', $tin);
    }

    /**
     * @return array<int, OvertimeEmployee>
     */
    public function getEmployees(): array
    {
        return $this->get('Ergazomenoi')['OvertimeErgazomenosDate'] ?? [];
    }

    public function getEmployee(int $index): ?OvertimeEmployee
    {
        return $this->get('Ergazomenoi')['OvertimeErgazomenosDate'][$index] ?? null;
    }

    /**
     * @param array<int, OvertimeEmployee> $employees
     */
    public function setEmployees(array $employees): static
    {
        return $this->set('Ergazomenoi', ['OvertimeErgazomenosDate' => $employees]);
    }

    public function addEmployee(OvertimeEmployee $employee): static
    {
        $employees = $this->getEmployees();
        $employees[] = $employee;

        return $this->setEmployees($employees);
    }
}
