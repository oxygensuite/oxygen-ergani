<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertime;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeRetrospective;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Responses\OvertimeResponse;

trait SendsOvertimeDocuments
{
    /**
     * Submit overtime declaration.
     *
     * @param WorkTime|WorkTime[] $workTime (entries of type WorkTimeType::OVERTIME)
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertime(WorkTime|array $workTime): array
    {
        return (new WorkTimeOvertime($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit overtime declaration for drivers.
     *
     * @param WorkTime|WorkTime[] $workTime (entries of type WorkTimeType::OVERTIME)
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertimeDrivers(WorkTime|array $workTime): array
    {
        return (new WorkTimeOvertimeDrivers($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit overtime declaration retrospective.
     *
     * @param WorkTime|WorkTime[] $workTime (entries of type WorkTimeType::OVERTIME)
     *
     * @return OvertimeResponse[]
     * @throws ErganiException
     */
    public function sendOvertimeRetrospective(WorkTime|array $workTime): array
    {
        return (new WorkTimeOvertimeRetrospective($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }
}
