<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\WorkingStatus\WorkingStatusChange;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsWorkingStatusDocuments
{
    /**
     * Submit working status change declaration.
     *
     * @param WorkingStatus|WorkingStatus[] $workingStatus
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendWorkingStatusChange(WorkingStatus|array $workingStatus): array
    {
        return (new WorkingStatusChange($this->accessToken, $this->environment, $this->config))
            ->handle($workingStatus);
    }
}
