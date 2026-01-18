<?php

namespace OxygenSuite\OxygenErgani\Models\WorkTime;

use OxygenSuite\OxygenErgani\Enums\DayOfWeek;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * @method static \OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEmployeeFactory factory(int $count = 1)
 */
class WorkTimeEmployee extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm',
        'f_eponymo',
        'f_onoma',
        'f_date',
        'f_day',
        'ErgazomenosAnalytics',
    ];

    public function getTin(): ?string
    {
        return $this->get('f_afm');
    }

    public function setTin(string $tin): static
    {
        return $this->set('f_afm', $tin);
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

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    public function setDate(string $date): static
    {
        return $this->set('f_date', $date);
    }

    public function getDay(): ?string
    {
        return $this->get('f_day');
    }

    public function setDay(string|DayOfWeek $day): static
    {
        if ($day instanceof DayOfWeek) {
            $day = $day->value;
        }

        return $this->set('f_day', $day);
    }

    /**
     * @return array<int, WorkTimeEntry>|null
     */
    public function getAnalytics(): ?array
    {
        return $this->get('ErgazomenosAnalytics')['ErgazomenosWTOAnalytics'] ?? null;
    }

    public function getAnalytic(int $index): ?WorkTimeEntry
    {
        return $this->get('ErgazomenosAnalytics')['ErgazomenosWTOAnalytics'][$index] ?? null;
    }

    /**
     * @param array<int, WorkTimeEntry> $analytics
     */
    public function setAnalytics(array $analytics): static
    {
        return $this->set('ErgazomenosAnalytics', ['ErgazomenosWTOAnalytics' => $analytics]);
    }

    public function addAnalytics(WorkTimeEntry $analytics): static
    {
        $currentAnalytics = $this->getAnalytics() ?? [];
        $currentAnalytics[] = $analytics;

        return $this->setAnalytics($currentAnalytics);
    }
}
