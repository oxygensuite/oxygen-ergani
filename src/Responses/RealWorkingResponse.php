<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class RealWorkingResponse extends Response
{
    public ?int $branchAa;
    public ?string $afm;
    public ?DateTimeInterface $date;
    public ?string $hourFrom;
    public ?string $hourTo;
    public ?bool $endsOnNextDay;

    protected function processData(): void
    {
        $this->branchAa = $this->int('Aa');
        $this->afm = $this->string('Afm');
        $this->date = $this->date('Date');
        $this->hourFrom = $this->string('HourFrom');
        $this->hourTo = $this->string('HourTo');
        $this->endsOnNextDay = $this->get('IsEndDateDifferentThanDate') !== null
            ? $this->bool('IsEndDateDifferentThanDate')
            : null;
    }
}
