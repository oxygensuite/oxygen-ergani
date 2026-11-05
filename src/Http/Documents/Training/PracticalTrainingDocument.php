<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E3.3 practical training start submissions.
 *
 * @see xsd/E33_v1.xsd
 */
abstract class PracticalTrainingDocument extends Documents
{
    /**
     * @param PracticalTrainingDeclaration|PracticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(PracticalTrainingDeclaration|array $declarations): array
    {
        if ($declarations instanceof PracticalTrainingDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE33' => [
                'AnaggeliaE33' => array_map(
                    fn(PracticalTrainingDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
