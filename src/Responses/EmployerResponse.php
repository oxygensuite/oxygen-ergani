<?php

namespace OxygenSuite\OxygenErgani\Responses;

class EmployerResponse extends Response
{
    public ?string $id;
    public ?string $tin;
    public ?string $name;
    public ?string $ame;
    public ?bool $isInCardSector;

    protected function processData(): void
    {
        $employer = $this->get('Ergodotis') ?? [];

        $this->id = $employer['Id'] ?? null;
        $this->tin = $employer['Afm'] ?? null;
        $this->name = $employer['Eponimia'] ?? null;
        $this->ame = $employer['Ame'] ?? null;
        $this->isInCardSector = isset($employer['IsInCardSector'])
            ? $employer['IsInCardSector'] === '1'
            : null;
    }
}
