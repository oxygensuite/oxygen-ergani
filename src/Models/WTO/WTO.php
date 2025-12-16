<?php

namespace OxygenSuite\OxygenErgani\Models\WTO;

use OxygenSuite\OxygenErgani\Models\Model;

class WTO extends Model
{
    protected array $expectedOrder = [
        "f_aa_pararthmatos",
        "f_rel_protocol",
        "f_rel_date",
        "f_comments",
        "f_from_date",
        "f_to_date",
        "Ergazomenoi",
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

    public function getFromDate(): ?string
    {
        return $this->get('f_from_date');
    }

    public function setFromDate(string $fromDate): static
    {
        return $this->set('f_from_date', $fromDate);
    }

    public function getToDate(): ?string
    {
        return $this->get('f_to_date');
    }

    public function setToDate(string $toDate): static
    {
        return $this->set('f_to_date', $toDate);
    }

    public function getEmployees(): array
    {
        return $this->get('Ergazomenoi')['ErgazomenoiWTO'] ?? [];
    }

    public function getEmployee(int $index): ?WTOEmployee
    {
        return $this->get('Ergazomenoi')['ErgazomenoiWTO'][$index] ?? null;
    }

    public function setEmployees(array $employees): static
    {
        return $this->set('Ergazomenoi', ['ErgazomenoiWTO' => $employees]);
    }

    public function addEmployee(WTOEmployee $employee): static
    {
        $employees = $this->getEmployees();
        $employees[] = $employee;
        return $this->setEmployees($employees);
    }
}
