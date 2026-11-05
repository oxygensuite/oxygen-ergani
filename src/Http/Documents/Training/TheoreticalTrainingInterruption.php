<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingInterruptionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for theoretical training interruption (E3.2 interruption / WebE32D) submissions.
 *
 * Reports the interruption of a training declared with a previous E3.2.
 *
 * @see xsd/E32D_v1.xsd
 */
class TheoreticalTrainingInterruption extends Documents
{
    private const ACTION = 'WebE32D';

    /**
     * @param TheoreticalTrainingInterruptionDeclaration|TheoreticalTrainingInterruptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(TheoreticalTrainingInterruptionDeclaration|array $declarations): array
    {
        if ($declarations instanceof TheoreticalTrainingInterruptionDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE32D' => [
                'AnaggeliaE32D' => array_map(
                    fn(TheoreticalTrainingInterruptionDeclaration $item) => $item->toSortedArray(),
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
