<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Construction;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for construction work census (WebE12Ap) submissions.
 *
 * Used for reporting monthly construction site personnel census to ERGANI.
 *
 * @see xsd/E12Apografiko_v1.xsd
 */
class ConstructionWorkCensus extends Documents
{
    private const ACTION = 'WebE12Ap';

    /**
     * @param ConstructionCensus|ConstructionCensus[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ConstructionCensus|array $declarations): array
    {
        if ($declarations instanceof ConstructionCensus) {
            $declarations = [$declarations];
        }

        $body = [
            'Amoes' => [
                'Amoe' => array_map(
                    fn(ConstructionCensus $item) => $item->toSortedArray(),
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
