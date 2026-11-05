<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramInterruptionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for community-benefit program employment interruption (E3.1 interruption / WebE31D) submissions.
 *
 * Reports the interruption of a beneficiary's employment declared with a previous E3.1.
 *
 * @see xsd/E31D_v1.xsd
 */
class CommunityProgramInterruption extends Documents
{
    private const ACTION = 'WebE31D';

    /**
     * @param CommunityProgramInterruptionDeclaration|CommunityProgramInterruptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(CommunityProgramInterruptionDeclaration|array $declarations): array
    {
        if ($declarations instanceof CommunityProgramInterruptionDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE31D' => [
                'AnaggeliaE31D' => array_map(
                    fn(CommunityProgramInterruptionDeclaration $item) => $item->toSortedArray(),
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
