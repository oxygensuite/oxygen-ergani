<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

class ServicesList extends Client
{
    private const URI = 'WebServices/ServicesList';

    /**
     * Retrieves all available services.
     *
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function handle(): array
    {
        return $this->get(self::URI)->json();
    }
}
