<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E3.2 theoretical training start submissions.
 *
 * @see xsd/E32_v1.xsd
 */
abstract class TheoreticalTrainingDocument extends Documents
{
    /**
     * @param TheoreticalTrainingDeclaration|TheoreticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(TheoreticalTrainingDeclaration|array $declarations): array
    {
        if ($declarations instanceof TheoreticalTrainingDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE32' => [
                'AnaggeliaE32' => array_map(
                    fn(TheoreticalTrainingDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
