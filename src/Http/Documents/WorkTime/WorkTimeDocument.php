<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkTime;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Responses\WorkTimeResponse;

abstract class WorkTimeDocument extends Documents
{
    /**
     * @param WorkTime|array<int, WorkTime> $workTime
     *
     * @return array<int, WorkTimeResponse>
     * @throws ErganiException
     */
    public function handle(WorkTime|array $workTime): array
    {
        if ($workTime instanceof WorkTime) {
            $workTime = [$workTime];
        }

        $body = ['WTOS' => ['WTO' => array_map(fn(WorkTime $item) => $item->toSortedArray(), $workTime)]];

        return $this->submit($body)->morphToArray(WorkTimeResponse::class);
    }
}
