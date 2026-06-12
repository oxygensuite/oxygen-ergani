<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class WorkTimeLeaveCorrection extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTOLeaveC';
    }
}
