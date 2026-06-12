<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\PreAnnouncement;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for pre-announcement exemption (ExProan) submissions.
 *
 * Used for declaring exemption from the pre-announcement requirement.
 *
 * @see xsd/ExProan.xsd
 */
class PreAnnouncementExemption extends Documents
{
    private const ACTION = 'ExProan';

    /**
     * @param ExemptionDeclaration|ExemptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ExemptionDeclaration|array $declarations): array
    {
        if ($declarations instanceof ExemptionDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'DhlwseisExProan' => [
                'DhlwshExProan' => array_map(
                    fn(ExemptionDeclaration $item) => $item->toSortedArray(),
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
