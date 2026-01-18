<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\EmployeeStatusResponse;

class MonthlyStatus extends Service
{
    /**
     * Retrieves monthly employee status for the specified year and month
     *
     * @param int $year  The report year (e.g., 2025)
     * @param int $month The report month (1-12)
     *
     * @return EmployeeStatusResponse[]
     * @throws ErganiException
     */
    public function handle(int $year, int $month): array
    {
        $data = $this->execute([
            'ReportYear' => (string) $year,
            'ReportMonth' => (string) $month,
        ])->json();

        $employees = $data[$this->serviceCode()]['Apasxoloumenoi'] ?? [];

        return array_map(
            fn(array $employee) => new EmployeeStatusResponse($employee),
            $employees,
        );
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_04';
    }
}
