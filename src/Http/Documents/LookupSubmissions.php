<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

class LookupSubmissions extends Client
{
    private const URI = 'Lookup/Submissions';

    /**
     * Retrieves all available submissions.
     *
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function handle(): array
    {
        return $this->get(self::URI)->json();
    }
}
