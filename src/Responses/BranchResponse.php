<?php

namespace OxygenSuite\OxygenErgani\Responses;

class BranchResponse extends Response
{
    public ?string $aa;
    public ?string $address;

    protected function processData(): void
    {
        $this->aa = $this->string('Aa');
        $this->address = $this->string('Address');
    }
}
