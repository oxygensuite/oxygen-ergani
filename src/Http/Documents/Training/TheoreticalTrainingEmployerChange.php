<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingEmployerChangeDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for theoretical training employer change (E3.2 employer change / WebE32T) submissions.
 *
 * Changes the practical training business of a beneficiary declared with a previous E3.2.
 *
 * @see xsd/E32T_v1.xsd
 */
class TheoreticalTrainingEmployerChange extends Documents
{
    private const ACTION = 'WebE32T';

    /**
     * @param TheoreticalTrainingEmployerChangeDeclaration|TheoreticalTrainingEmployerChangeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(TheoreticalTrainingEmployerChangeDeclaration|array $declarations): array
    {
        if ($declarations instanceof TheoreticalTrainingEmployerChangeDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE32T' => [
                'AnaggeliaE32T' => array_map(
                    fn(TheoreticalTrainingEmployerChangeDeclaration $item) => $item->toSortedArray(),
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
