<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertime;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeRetrospective;
use OxygenSuite\OxygenErgani\Models\Overtime\Overtime;
use OxygenSuite\OxygenErgani\Responses\OvertimeResponse;

trait SendsOvertimeDocuments
{
    /**
     * Submit overtime declaration.
     *
     * @param Overtime|Overtime[] $overtime
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertime(Overtime|array $overtime): array
    {
        return (new WorkTimeOvertime($this->accessToken, $this->environment, $this->config))
            ->handle($overtime);
    }

    /**
     * Submit overtime declaration for drivers.
     *
     * @param Overtime|Overtime[] $overtime
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertimeDrivers(Overtime|array $overtime): array
    {
        return (new WorkTimeOvertimeDrivers($this->accessToken, $this->environment, $this->config))
            ->handle($overtime);
    }

    /**
     * Submit overtime declaration retrospective.
     *
     * @param Overtime|Overtime[] $overtime
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertimeRetrospective(Overtime|array $overtime): array
    {
        return (new WorkTimeOvertimeRetrospective($this->accessToken, $this->environment, $this->config))
            ->handle($overtime);
    }
}
