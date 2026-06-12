<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\DigitalWorkTimeResponse;

class DigitalWorkTimeStatus extends Service
{
    /**
     * Retrieves the current digital work time organization (ΨΟΧΕ) status
     * for a branch on a given date.
     *
     * Only available on the trial environment. Dates are restricted to the
     * previous month and earlier.
     *
     * @param int $branchAa Branch number (PararthmaAa)
     * @param DateTime|string $date Reference date (DD/MM/YYYY)
     *
     * @return DigitalWorkTimeResponse[]
     * @throws ErganiException
     */
    public function handle(int $branchAa, DateTime|string $date): array
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        $data = $this->execute([
            'PararthmaAa' => $branchAa,
            'Date' => $date,
        ])->json();

        // API returns a literal null body when no data exists
        if (!is_array($data)) {
            return [];
        }

        $entries = $data[$this->serviceCode()]['Working'] ?? [];

        // API returns object for single entry, array for multiple
        // Normalize to always be an array of entries
        if ($entries !== [] && !array_is_list($entries)) {
            $entries = [$entries];
        }

        return array_map(
            fn(array $entry) => new DigitalWorkTimeResponse($entry),
            $entries,
        );
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_08';
    }
}
