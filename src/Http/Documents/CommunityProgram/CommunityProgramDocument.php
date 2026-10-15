<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Base handler for E3.1 community-benefit program employment submissions.
 *
 * @see xsd/E31_v1.xsd
 */
abstract class CommunityProgramDocument extends Documents
{
    /**
     * @param CommunityProgramDeclaration|CommunityProgramDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(CommunityProgramDeclaration|array $declarations): array
    {
        if ($declarations instanceof CommunityProgramDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE31' => [
                'AnaggeliaE31' => array_map(
                    fn(CommunityProgramDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }
}
