<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class WeeklyWorkTime extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTOWeek';
    }
}
