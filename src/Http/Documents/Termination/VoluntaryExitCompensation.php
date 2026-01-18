<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for voluntary exit with compensation (E5E) submissions.
 *
 * This is used for reporting voluntary exit with severance pay,
 * typically in voluntary separation programs.
 *
 * @see xsd/E5E_v1.xsd
 */
class VoluntaryExitCompensation extends Documents
{
    private const ACTION = 'WebE5E';

    /**
     * @param CompensatedExitDeclaration|CompensatedExitDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(CompensatedExitDeclaration|array $declarations): array
    {
        if ($declarations instanceof CompensatedExitDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5E' => [
                'AnaggeliaE5E' => array_map(
                    fn(CompensatedExitDeclaration $item) => $item->toSortedArray(),
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
