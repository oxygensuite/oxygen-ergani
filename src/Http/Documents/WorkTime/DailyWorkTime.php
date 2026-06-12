<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class DailyWorkTime extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTODaily';
    }
}
