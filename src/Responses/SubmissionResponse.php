<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class SubmissionResponse extends Response
{
    public ?string $id = null;
    public ?string $protocol = null;
    public ?DateTimeInterface $submissionDate = null;

    protected function processData(): void
    {
        $this->id = $this->string('id');
        $this->protocol = $this->string('protocol');
        $this->submissionDate = $this->date('submitDate', 'd/m/Y H:i', 'Europe/Athens');
    }
}
