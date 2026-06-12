<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class DailyWorkTimeRetrospective extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTODailyA';
    }
}
