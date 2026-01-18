<?php

namespace OxygenSuite\OxygenErgani\Models\Overtime;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

class OvertimeEmployee extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm',
        'f_amka',
        'f_eponymo',
        'f_onoma',
        'f_date',
        'f_from',
        'f_to',
        'f_cancellation',
        'f_step',
        'f_reason',
        'f_weekdates',
        'f_asee',
    ];

    public function getTin(): ?string
    {
        return $this->get('f_afm');
    }

    public function setTin(string $tin): static
    {
        return $this->set('f_afm', $tin);
    }

    public function getAmka(): ?string
    {
        return $this->get('f_amka');
    }

    public function setAmka(string $amka): static
    {
        return $this->set('f_amka', $amka);
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

    public function getFromTime(): ?string
    {
        return $this->get('f_from');
    }

    public function setFromTime(string $from): static
    {
        return $this->set('f_from', $from);
    }

    public function getToTime(): ?string
    {
        return $this->get('f_to');
    }

    public function setToTime(string $to): static
    {
        return $this->set('f_to', $to);
    }

    public function getCancellation(): ?string
    {
        return $this->get('f_cancellation');
    }

    public function setCancellation(string $cancellation): static
    {
        return $this->set('f_cancellation', $cancellation);
    }

    public function getStep(): ?string
    {
        return $this->get('f_step');
    }

    public function setStep(string $step): static
    {
        return $this->set('f_step', $step);
    }

    public function getReason(): ?string
    {
        return $this->get('f_reason');
    }

    public function setReason(string $reason): static
    {
        return $this->set('f_reason', $reason);
    }

    public function getWeekDates(): ?string
    {
        return $this->get('f_weekdates');
    }

    public function setWeekDates(string $weekDates): static
    {
        return $this->set('f_weekdates', $weekDates);
    }

    public function getAsee(): ?string
    {
        return $this->get('f_asee');
    }

    public function setAsee(string $asee): static
    {
        return $this->set('f_asee', $asee);
    }
}
