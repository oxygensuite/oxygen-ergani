<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\EmployerResponse;

class EmployerInfo extends Service
{
    /**
     * Retrieves employer information
     *
     * @throws ErganiException
     */
    public function handle(): EmployerResponse
    {
        $data = $this->execute()->json();

        return new EmployerResponse($data[$this->serviceCode()] ?? []);
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_01';
    }
}
