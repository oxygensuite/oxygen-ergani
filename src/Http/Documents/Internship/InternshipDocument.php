<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E3.5 internship declaration submissions (start / correction).
 *
 * @see xsd/E35_v3.xsd
 */
abstract class InternshipDocument extends Documents
{
    /**
     * @param InternshipDeclaration|InternshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(InternshipDeclaration|array $declarations): array
    {
        if ($declarations instanceof InternshipDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'PraktikesE35' => [
                'PraktikhE35' => array_map(
                    fn(InternshipDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
