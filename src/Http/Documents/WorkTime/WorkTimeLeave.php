<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

class WorkTimeLeave extends WorkTimeDocument
{
    protected function action(): string
    {
        return 'WTOLeave';
    }
}
