<?php

namespace OxygenSuite\OxygenErgani\Responses;

class ParameterResponse extends Response
{
    public ?string $code;
    public ?string $description;
    public ?string $extra;

    protected function processData(): void
    {
        $this->code = $this->string('Code');
        $this->description = $this->string('Description');
        $this->extra = $this->string('Extra');
    }
}
