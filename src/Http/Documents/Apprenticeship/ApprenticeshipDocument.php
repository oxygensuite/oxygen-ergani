<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E3.4 apprenticeship start submissions.
 *
 * @see xsd/E34_v1.xsd
 */
abstract class ApprenticeshipDocument extends Documents
{
    /**
     * @param ApprenticeshipDeclaration|ApprenticeshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ApprenticeshipDeclaration|array $declarations): array
    {
        if ($declarations instanceof ApprenticeshipDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE34' => [
                'AnaggeliaE34' => array_map(
                    fn(ApprenticeshipDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
