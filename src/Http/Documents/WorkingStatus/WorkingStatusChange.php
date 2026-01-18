<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkingStatus;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

class WorkingStatusChange extends Documents
{
    private const ACTION = 'WKChgWK';

    /**
     * @param WorkingStatus|WorkingStatus[] $workingStatus
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(WorkingStatus|array $workingStatus): array
    {
        if ($workingStatus instanceof WorkingStatus) {
            $workingStatus = [$workingStatus];
        }

        $body = [
            'WorkingStatusChanges' => [
                'WorkingStatusChange' => array_map(
                    fn(WorkingStatus $item) => $item->toSortedArray(),
                    $workingStatus,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }

    protected function action(): string
    {
        return self::ACTION;
    }
}
