<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E14 parental leave submissions.
 *
 * @see xsd/E14_v1.xsd
 */
abstract class ParentalLeaveDocument extends Documents
{
    /**
     * @param ParentalLeaveDeclaration|ParentalLeaveDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ParentalLeaveDeclaration|array $declarations): array
    {
        if ($declarations instanceof ParentalLeaveDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'ParentalLeavesE14' => [
                'ParentalLeaveE14' => array_map(
                    fn(ParentalLeaveDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
