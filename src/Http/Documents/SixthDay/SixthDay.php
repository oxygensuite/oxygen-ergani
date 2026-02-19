<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\SixthDay;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for sixth day / extra shift (SixthDay) submissions.
 *
 * Used for declaring employment on the 6th day of the week.
 *
 * @see xsd/SixthDay_v2.xsd
 */
class SixthDay extends Documents
{
    private const ACTION = 'SixthDay';

    /**
     * @param SixthDayDeclaration|SixthDayDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(SixthDayDeclaration|array $declarations): array
    {
        if ($declarations instanceof SixthDayDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'DhlwseisSixthDay' => [
                'DhlwshSixthDay' => array_map(
                    fn(SixthDayDeclaration $item) => $item->toSortedArray(),
                    $declarations,
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
