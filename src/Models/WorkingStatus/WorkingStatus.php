<?php

namespace OxygenSuite\OxygenErgani\Models\WorkingStatus;

use OxygenSuite\OxygenErgani\Factories\WorkingStatus\WorkingStatusFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Working status change model for WKChgWK schema.
 *
 * Used for declaring changes to employee working conditions.
 *
 * @method static WorkingStatusFactory factory(int $count = 1)
 */
class WorkingStatus extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
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

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * @return array<int, WorkingStatusEmployee>
     */
    public function getEmployees(): array
    {
        return $this->get('Ergazomenoi')['Ergazomenos'] ?? [];
    }

    public function getEmployee(int $index): ?WorkingStatusEmployee
    {
        return $this->get('Ergazomenoi')['Ergazomenos'][$index] ?? null;
    }

    /**
     * @param array<int, WorkingStatusEmployee> $employees
     */
    public function setEmployees(array $employees): static
    {
        return $this->set('Ergazomenoi', ['Ergazomenos' => $employees]);
    }

    public function addEmployee(WorkingStatusEmployee $employee): static
    {
        $employees = $this->getEmployees();
        $employees[] = $employee;

        return $this->setEmployees($employees);
    }
}
