<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Overtime;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Overtime\Overtime;
use OxygenSuite\OxygenErgani\Responses\OvertimeResponse;

abstract class OvertimeDocument extends Documents
{
    /**
     * @param Overtime|array<int, Overtime> $overtime
     *
     * @return array<int, OvertimeResponse>
     * @throws ErganiException
     */
    public function handle(Overtime|array $overtime): array
    {
        if ($overtime instanceof Overtime) {
            $overtime = [$overtime];
        }

        $body = ['Overtimes' => ['Overtime' => array_map(fn(Overtime $item) => $item->toSortedArray(), $overtime)]];

        return $this->submit($body)->morphToArray(OvertimeResponse::class);
    }
}
