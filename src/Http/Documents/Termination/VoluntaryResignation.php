<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for voluntary resignation (E5N) submissions.
 *
 * @see xsd/E5N_v1.xsd
 */
class VoluntaryResignation extends Documents
{
    private const ACTION = 'WebE5N';

    /**
     * @param VoluntaryResignationDeclaration|VoluntaryResignationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(VoluntaryResignationDeclaration|array $declarations): array
    {
        if ($declarations instanceof VoluntaryResignationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5N' => [
                'AnaggeliaE5N' => array_map(
                    fn(VoluntaryResignationDeclaration $item) => $item->toSortedArray(),
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
