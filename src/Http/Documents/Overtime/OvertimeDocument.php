<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Overtime;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Responses\OvertimeResponse;

/**
 * Base handler for overtime submissions (WTOOv, WTOOvA, WTOOvD).
 *
 * ERGANI II overtime uses the work time organization structure (WTOS/WTO, WTO_v1.xsd):
 * a WorkTime with employees whose entries have type WorkTimeType::OVERTIME (ΥΠ).
 */
abstract class OvertimeDocument extends Documents
{
    /**
     * @param WorkTime|array<int, WorkTime> $workTime
     *
     * @return array<int, OvertimeResponse>
     * @throws ErganiException
     */
    public function handle(WorkTime|array $workTime): array
    {
        if ($workTime instanceof WorkTime) {
            $workTime = [$workTime];
        }

        $body = ['WTOS' => ['WTO' => array_map(fn(WorkTime $item) => $item->toSortedArray(), $workTime)]];

        return $this->submit($body)->morphToArray(OvertimeResponse::class);
    }
}
