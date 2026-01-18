<?php

namespace OxygenSuite\OxygenErgani\Responses;

class EmployeeStatusResponse extends Response
{
    public ?string $afm;
    public ?string $amka;
    public ?string $lastName;
    public ?string $firstName;
    public ?string $fromDate;
    public ?string $toDate;
    public ?string $specialty;
    public ?string $salary;

    protected function processData(): void
    {
        $this->afm = $this->string('Afm');
        $this->amka = $this->string('Amka');
        $this->lastName = $this->string('Eponymo');
        $this->firstName = $this->string('Onoma');
        $this->fromDate = $this->string('FromDate');
        $this->toDate = $this->string('ToDate');
        $this->specialty = $this->string('Eidikothta');
        $this->salary = $this->string('Apodoxes');
    }
}
