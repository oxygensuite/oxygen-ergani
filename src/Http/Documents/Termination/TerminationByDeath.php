<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for death termination (E5D) submissions.
 *
 * This is used for reporting termination of employment due to employee death.
 *
 * @see xsd/E5D_v1.xsd
 */
class TerminationByDeath extends Documents
{
    private const ACTION = 'WebE5D';

    /**
     * @param DeathTerminationDeclaration|DeathTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(DeathTerminationDeclaration|array $declarations): array
    {
        if ($declarations instanceof DeathTerminationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5D' => [
                'AnaggeliaE5D' => array_map(
                    fn(DeathTerminationDeclaration $item) => $item->toSortedArray(),
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
