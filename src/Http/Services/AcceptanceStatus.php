<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\AcceptanceStatusResponse;

class AcceptanceStatus extends Service
{
    /**
     * Retrieves the acceptance status of essential terms declarations.
     *
     * @param string $tin Employee tax identification number
     * @param string $protocol Declaration protocol number
     * @param DateTime|string $date Declaration submission date (DD/MM/YYYY)
     *
     * @throws ErganiException
     */
    public function handle(string $tin, string $protocol, DateTime|string $date): ?AcceptanceStatusResponse
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        $data = $this->execute([
            'afm' => $tin,
            'protocol' => $protocol,
            'date' => $date,
        ])->json();

        if (!is_array($data)) {
            return null;
        }

        $result = $data[$this->serviceCode()] ?? null;

        if ($result === null) {
            return null;
        }

        return new AcceptanceStatusResponse($result);
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_06';
    }
}
