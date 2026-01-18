<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class DailyWorkTimeDrivers extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTODailyD';
    }
}
