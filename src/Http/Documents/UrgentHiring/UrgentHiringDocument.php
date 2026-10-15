<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for urgent hiring (FastAna) submissions.
 *
 * @see xsd/FastAna_v1.xsd
 */
abstract class UrgentHiringDocument extends Documents
{
    /**
     * @param UrgentHiringDeclaration|UrgentHiringDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(UrgentHiringDeclaration|array $declarations): array
    {
        if ($declarations instanceof UrgentHiringDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'FastAnaggelies' => [
                'FastAnaggelia' => array_map(
                    fn(UrgentHiringDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
