<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramExtensionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for community-benefit program employment extension (E3.1 extension / WebE31E) submissions.
 *
 * Extends the employment of a beneficiary declared with a previous E3.1.
 *
 * @see xsd/E31E_v1.xsd
 */
class CommunityProgramExtension extends Documents
{
    private const ACTION = 'WebE31E';

    /**
     * @param CommunityProgramExtensionDeclaration|CommunityProgramExtensionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(CommunityProgramExtensionDeclaration|array $declarations): array
    {
        if ($declarations instanceof CommunityProgramExtensionDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE31E' => [
                'AnaggeliaE31E' => array_map(
                    fn(CommunityProgramExtensionDeclaration $item) => $item->toSortedArray(),
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
