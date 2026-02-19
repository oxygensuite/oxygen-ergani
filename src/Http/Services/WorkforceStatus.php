<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\WorkforceStatusResponse;

class WorkforceStatus extends Service
{
    /**
     * Retrieves current workforce status, optionally filtered by employee TIN.
     *
     * @param string|null $tin Employee tax identification number (optional)
     *
     * @return WorkforceStatusResponse[]
     * @throws ErganiException
     */
    public function handle(?string $tin = null): array
    {
        $parameters = $tin !== null ? ['afm' => $tin] : [];

        $data = $this->execute($parameters)->json();

        $employees = $data[$this->serviceCode()]['Cur'] ?? [];

        // API returns object for single employee, array for multiple
        // Normalize to always be an array of employees
        if ($employees !== [] && !array_is_list($employees)) {
            $employees = [$employees];
        }

        return array_map(
            fn(array $employee) => new WorkforceStatusResponse($employee),
            $employees,
        );
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_05';
    }
}
