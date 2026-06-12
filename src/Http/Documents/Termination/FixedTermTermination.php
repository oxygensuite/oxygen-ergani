<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for fixed-term contract termination (E7N) submissions.
 *
 * Used for reporting fixed-term employment contract terminations to ERGANI.
 * This is distinct from E5 (employee-initiated) and E6 (employer-initiated dismissals).
 *
 * @see xsd/E7N_v1.xsd
 */
class FixedTermTermination extends Documents
{
    private const ACTION = 'WebE7N';

    /**
     * @param FixedTermTerminationDeclaration|FixedTermTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(FixedTermTerminationDeclaration|array $declarations): array
    {
        if ($declarations instanceof FixedTermTerminationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE7N' => [
                'AnaggeliaE7N' => array_map(
                    fn(FixedTermTerminationDeclaration $item) => $item->toSortedArray(),
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
