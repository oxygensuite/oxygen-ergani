<?php

namespace OxygenSuite\OxygenErgani\Models\WorkTime;

use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * @method static \OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEntryFactory factory(int $count = 1)
 */
class WorkTimeEntry extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_type',
        'f_from',
        'f_to',
        'f_year',
        'f_req_days',
    ];

    public function getType(): ?string
    {
        return $this->get('f_type');
    }

    public function setType(string|WorkTimeType $type): static
    {
        if ($type instanceof WorkTimeType) {
            $type = $type->value;
        }

        return $this->set('f_type', $type);
    }

    public function getFromTime(): ?string
    {
        return $this->get('f_from');
    }

    public function setFromTime(string $fromDate): static
    {
        return $this->set('f_from', $fromDate);
    }

    public function getToTime(): ?string
    {
        return $this->get('f_to');
    }

    public function setToTime(string $toDate): static
    {
        return $this->set('f_to', $toDate);
    }

    public function getYear(): ?string
    {
        return $this->get('f_year');
    }

    public function setYear(string $year): static
    {
        return $this->set('f_year', $year);
    }

    public function getRequestedDays(): ?string
    {
        return $this->get('f_req_days');
    }

    public function setRequestedDays(string $days): static
    {
        return $this->set('f_req_days', $days);
    }
}
