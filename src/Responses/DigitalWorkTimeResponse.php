<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class DigitalWorkTimeResponse extends Response
{
    public ?int $branchAa;
    public ?string $afm;
    public ?DateTimeInterface $date;
    public ?string $type;
    public ?string $hourFrom;
    public ?string $hourTo;
    public ?string $extra;
    public ?int $breakMinutes;
    public ?bool $breakInWork;

    protected function processData(): void
    {
        $this->branchAa = $this->int('Aa');
        $this->afm = $this->string('Afm');
        $this->date = $this->date('Date');
        $this->type = $this->string('Type');
        $this->hourFrom = $this->string('HourFrom');
        $this->hourTo = $this->string('HourTo');
        $this->extra = $this->string('Extra');
        $this->breakMinutes = $this->int('BreakMinutes');
        $this->breakInWork = $this->get('BreakInWork') !== null ? $this->bool('BreakInWork') : null;
    }
}
