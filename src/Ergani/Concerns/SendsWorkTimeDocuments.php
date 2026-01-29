<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeRetrospective;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeave;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeaveCorrection;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Responses\WorkTimeResponse;

trait SendsWorkTimeDocuments
{
    /**
     * Submit daily work time organization declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendDailyWorkTime(WorkTime|array $workTime): array
    {
        return (new DailyWorkTime($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit weekly work time organization declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendWeeklyWorkTime(WorkTime|array $workTime): array
    {
        return (new WeeklyWorkTime($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit daily work time organization for drivers declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendDailyWorkTimeDrivers(WorkTime|array $workTime): array
    {
        return (new DailyWorkTimeDrivers($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit daily work time organization retrospective declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendDailyWorkTimeRetrospective(WorkTime|array $workTime): array
    {
        return (new DailyWorkTimeRetrospective($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit work time leave declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendWorkTimeLeave(WorkTime|array $workTime): array
    {
        return (new WorkTimeLeave($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }

    /**
     * Submit work time leave correction declaration.
     *
     * @param WorkTime|WorkTime[] $workTime
     *
     * @return WorkTimeResponse[]
     * @throws ErganiException
     */
    public function sendWorkTimeLeaveCorrection(WorkTime|array $workTime): array
    {
        return (new WorkTimeLeaveCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($workTime);
    }
}
