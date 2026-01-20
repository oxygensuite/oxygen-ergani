<?php

namespace OxygenSuite\OxygenErgani\Models\WorkTime;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * @method static \OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeFactory factory(int $count = 1)
 */
class WorkTime extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_rel_protocol',
        'f_rel_date',
        'f_comments',
        'f_from_date',
        'f_to_date',
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

    public function setRelatedDate(DateTime|string $relatedDate): static
    {
        if ($relatedDate instanceof DateTime) {
            $relatedDate = $relatedDate->format('d/m/Y');
        }

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

    public function getFromDate(): ?string
    {
        return $this->get('f_from_date');
    }

    public function setFromDate(DateTime|string $fromDate): static
    {
        if ($fromDate instanceof DateTime) {
            $fromDate = $fromDate->format('d/m/Y');
        }

        return $this->set('f_from_date', $fromDate);
    }

    public function getToDate(): ?string
    {
        return $this->get('f_to_date');
    }

    public function setToDate(DateTime|string $toDate): static
    {
        if ($toDate instanceof DateTime) {
            $toDate = $toDate->format('d/m/Y');
        }

        return $this->set('f_to_date', $toDate);
    }

    /**
     * @return array<int, WorkTimeEmployee>
     */
    public function getEmployees(): array
    {
        return $this->get('Ergazomenoi')['ErgazomenoiWTO'] ?? [];
    }

    public function getEmployee(int $index): ?WorkTimeEmployee
    {
        return $this->get('Ergazomenoi')['ErgazomenoiWTO'][$index] ?? null;
    }

    /**
     * @param array<int, WorkTimeEmployee> $employees
     */
    public function setEmployees(array $employees): static
    {
        return $this->set('Ergazomenoi', ['ErgazomenoiWTO' => $employees]);
    }

    public function addEmployee(WorkTimeEmployee $employee): static
    {
        $employees = $this->getEmployees();
        $employees[] = $employee;

        return $this->setEmployees($employees);
    }
}
